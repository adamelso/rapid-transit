<?php

namespace spec\Transit\WebBundle\Ssh;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class KeygenSpec extends ObjectBehavior
{
    function let(\Crypt_RSA $rsa)
    {
        $this->beConstructedWith($rsa);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Transit\WebBundle\Ssh\Keygen');
    }

    function it_generates_SSH_key_pairs(\Crypt_RSA $rsa)
    {
        $publicKey = 'ssh-rsa AAAAAAhhhhhoooooooyeah+324fnwe\dewf43er me@example.com';

        $privateKey = <<<EOF
-----BEGIN RSA PRIVATE KEY-----
ijdeluwbdlebwfhbwbfueiwbfuewibfoiewiubfewbfiewui
-----END RSA PRIVATE KEY-----
EOF;

        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH)->shouldBeCalled();

        $rsa->createKey()->willReturn([
            'publickey' => $publicKey,
            'privatekey' => $privateKey,
            'partialkey' => false,
        ]);

        $this->generate()->shouldReturn([
            'publickey' => $publicKey,
            'privatekey' => $privateKey,
            'partialkey' => false,
        ]);
    }
}
