<?php

namespace spec\Transit\WebBundle\Hook;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PayloadSignerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('secret');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Transit\WebBundle\Hook\PayloadSigner');
    }

    function it_signs_a_payload()
    {
        $this->sign('payload')->shouldBe('f75efc0f29bf50c23f99b30b86f7c78fdaf5f11d');
    }
}
