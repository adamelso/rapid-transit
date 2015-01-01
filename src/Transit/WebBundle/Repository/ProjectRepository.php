<?php

namespace Transit\WebBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ODM\MongoDB\DocumentRepository;
use Transit\WebBundle\Model\Project;
use Transit\WebBundle\Model\UserAccount;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class ProjectRepository extends DocumentRepository
{
    /**
     * @param UserAccount $user
     *
     * @return $this
     */
    public function createNewWithUser(UserAccount $user)
    {
        /** @var Project $project */
        $project = $this->createNew();

        return $project->setOwner($user);
    }

    /**
     * @param string $name
     * @param string $repositoryUrl
     *
     * @return Project
     */
    public function createNewFromImports($name, $repositoryUrl)
    {
        /** @var Project $project */
        $project = $this->createNew();

        $project->setName($name);
        $project->setRepositoryUrl($repositoryUrl);

        return $project;
    }
}
