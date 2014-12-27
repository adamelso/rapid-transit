<?php

namespace Transit\WebBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\Process\Process;
use Transit\WebBundle\Model\Deployment;
use Transit\WebBundle\Model\Project;
use Transit\WebBundle\Repository\ProjectRepository;

class ExecuteDeploymentListener
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * @param ProjectRepository $projectRepository
     * @param Filesystem        $filesystem
     * @param LoggerInterface   $logger
     * @param string            $kernelRootDir
     */
    public function __construct(ProjectRepository $projectRepository, Filesystem $filesystem, LoggerInterface $logger, $kernelRootDir)
    {
        $this->projectRepository = $projectRepository;
        $this->filesystem = $filesystem;
        $this->logger = $logger;
        $this->kernelRootDir = $kernelRootDir;
    }

    /**
     * @param PostResponseEvent $event
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        $request = $event->getRequest();

        if (! ($request->request->has('transit_deployment') && 'transit_deployment_create' === $request->attributes->get('_route'))) {
            return;
        }

        $project = $this->projectRepository->find($request->attributes->get('projectId'));

        if (!$project instanceof Project) {
            return;
        }

        /** @var Deployment $deployment */
        $deployment = $project->getDeployments()->last();

        if ($deployment->isQueued()) {
            $this->deploy($deployment);
        }
    }

    /**
     * @todo Move to a service, use custom events, or message queues.
     *
     * @param Deployment $deployment
     */
    private function deploy(Deployment $deployment)
    {
        $manager = $this->projectRepository->getDocumentManager();

        $name = $deployment->getProject()->getName();

        $deployment->setStatus(Deployment::IN_PROGRESS);
        $manager->persist($deployment);
        $manager->flush();

        $this->logger->info('Executing as user ' . `whoami`);

        $this->logger->info('Cloning project ' . $name);

        $url = 'git@github.com:adamelso/archfizz.git';

        $repositoriesDir = implode(DIRECTORY_SEPARATOR, [
            $this->kernelRootDir,
            '..', 'var', 'repositories',
        ]);

        if (!$this->filesystem->exists($repositoriesDir)) {
            $this->filesystem->mkdir($repositoriesDir);
        }

        $cloneDir = implode(DIRECTORY_SEPARATOR, [$repositoriesDir, $name]);

        if ($this->filesystem->exists($cloneDir)) {
            $this->filesystem->remove($cloneDir);
        }

        $process = new Process(sprintf(
            'git clone --branch %s --depth 50 %s %s',
            $deployment->getBranch(), $url, $cloneDir
        ));

        $process->run();

        if (!$process->isSuccessful()) {
            $this->markDeploymentAsFailure($deployment, $name, $process->getErrorOutput(), $manager);
            return;
        }

        $this->logger->info('Deploying project ' . $name);

        /**
         * @todo Check for Gemfile.lock
         */
        $process = new Process(sprintf(
            'bundle install --path vendor/bundle', $cloneDir
        ), $cloneDir);

        $process->run();

        if (!$process->isSuccessful()) {
            $this->markDeploymentAsFailure($deployment, $name, $process->getErrorOutput(), $manager);
            return;
        }

        /**
         * @todo Decouple from Capistrano
         */
        $process = new Process(sprintf(
            'bundle exec cap deploy', $cloneDir
        ), $cloneDir);

        $process->run();

        if (!$process->isSuccessful()) {
            $this->markDeploymentAsFailure($deployment, $name, $process->getErrorOutput(), $manager);
            return;
        }

        $deployment->setStatus(Deployment::SUCCESSFUL);

        $manager->persist($deployment);
        $manager->flush();
    }

    /**
     * @param Deployment    $deployment
     * @param string        $name
     * @param ObjectManager $manager
     */
    private function markDeploymentAsFailure(Deployment $deployment, $name, $err, $manager)
    {
        $message = sprintf('Failed to deploy project `%s`', $name);
        $message .= $err;

        $this->logger->warning($message);
        $deployment->setStatus(Deployment::FAILED);

        $manager->persist($deployment);
        $manager->flush();
    }
}
