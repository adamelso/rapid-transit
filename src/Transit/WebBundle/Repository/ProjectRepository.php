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
     * @param mixed $user
     *
     * @return $this
     */
    public function createNewWithUser(UserAccount $user)
    {
        /** @var Project $project */
        $project = $this->createNew();

        return $project->setOwner($user);
    }
}
