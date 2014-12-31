<?php

namespace Transit\WebBundle\Repository;

use Doctrine\Common\Collections\Criteria;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\VarDumper\VarDumper;
use Transit\WebBundle\Model\Deployment;
use Transit\WebBundle\Model\Project;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class DeploymentRepository extends DocumentRepository
{
    public function createNewWithProject($projectId)
    {
        /** @var Deployment $deployment */
        $deployment = $this->createNew();
        $project = $this->getDocumentManager()->getRepository('Transit\WebBundle\Model\Project')->find($projectId);

        if (!$project instanceof Project) {
            throw new \InvalidArgumentException("Project not found.");
        }

        $deployment->setProject($project);

        return $deployment;
    }

    /**
     * @param Project $project
     *
     * @return array|null|object
     */
    public function findLastDeploymentNumberForProject(Project $project)
    {
        $qb = $this->createQueryBuilder();
        $qb
            ->field('project')->equals(new \MongoId($project->getId()))
            ->sort('number', Criteria::DESC)
            ->limit(1);

        $c=  $qb->getQuery()->getSingleResult();

        VarDumper::dump($qb->getQuery()->getQuery());

        return $c->getNumber();
    }
}
