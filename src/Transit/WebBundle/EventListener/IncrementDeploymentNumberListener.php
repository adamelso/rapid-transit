<?php

namespace Transit\WebBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Transit\WebBundle\Model\Deployment;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class IncrementDeploymentNumberListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (! $object instanceof Deployment) {
            return;
        }

        $lastDeploymentNumber = $object->getProject()->getDeployments()->last()->getNumber();

        $object->setNumber($lastDeploymentNumber ? ++$lastDeploymentNumber : 1);
    }
}
