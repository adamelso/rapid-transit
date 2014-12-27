<?php

namespace Transit\WebBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\MongoDB\Events;
use Transit\WebBundle\Model\SshKeyPair;
use Transit\WebBundle\Ssh\Keygen;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class GenerateSshKeysSubscriber implements EventSubscriber
{
    /**
     * @var Keygen
     */
    private $sshKeygen;

    /**
     * @param Keygen $sshKeygen
     */
    public function __construct(Keygen $sshKeygen)
    {
        $this->sshKeygen = $sshKeygen;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return ['prePersist'];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $sshKeyPair = $args->getObject();

        if (! $sshKeyPair instanceof SshKeyPair) {
            return;
        }

        $keys = $this->sshKeygen->generate();

        $sshKeyPair->setPublicKey($keys['publickey']);
        $sshKeyPair->setPrivateKey($keys['privatekey']);
    }
}
