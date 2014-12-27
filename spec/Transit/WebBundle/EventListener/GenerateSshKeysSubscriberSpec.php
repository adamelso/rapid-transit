<?php

namespace spec\Transit\WebBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Transit\WebBundle\Model\SshKeyPair;
use Transit\WebBundle\Ssh\Keygen;

class GenerateSshKeysSubscriberSpec extends ObjectBehavior
{
    function let(Keygen $sshKeygen)
    {
        $this->beConstructedWith($sshKeygen);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Transit\WebBundle\EventListener\GenerateSshKeysSubscriber');
    }

    function it_is_a_Doctrine_Event_Subscriber()
    {
        $this->shouldImplement('Doctrine\Common\EventSubscriber');
    }

    function it_listens_to_events_for_newly_persisted_objects()
    {
        $this->getSubscribedEvents()->shouldBe(['prePersist']);
    }

    function it_ignores_obejcts_that_are_not_SSH_Key_Pairs(LifecycleEventArgs $args, Keygen $sshKeygen, \stdClass $object)
    {
        $args->getObject()->willReturn($object);

        $sshKeygen->generate()->shouldNotBeCalled();

        $this->prePersist($args);
    }

    function it_generates_public_and_private_SSH_key_values_and_assigns_them_to_the_new_SSH_Key_Pair(LifecycleEventArgs $args, Keygen $sshKeygen, SshKeyPair $sshKeyPair)
    {
        $args->getObject()->willReturn($sshKeyPair);

        $sshKeygen->generate()->willReturn([
            'publickey' => 'ssh-rsa pU8l1ck3y adam@example.com',
            'privatekey' => "=== BEGIN PRIVATE KEY ===\nprIv4t3k3y\n=== END PRIVATE KEY ===",
        ]);

        $sshKeyPair->setPublicKey('ssh-rsa pU8l1ck3y adam@example.com')->shouldBeCalled();
        $sshKeyPair->setPrivateKey("=== BEGIN PRIVATE KEY ===\nprIv4t3k3y\n=== END PRIVATE KEY ===")->shouldBeCalled();

        $this->prePersist($args);
    }
}
