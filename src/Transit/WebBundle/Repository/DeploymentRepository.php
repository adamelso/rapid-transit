<?php

namespace Transit\WebBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ODM\MongoDB\DocumentRepository;
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
}
