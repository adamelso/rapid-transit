<?php

namespace spec\Transit\WebBundle\Monolog\Processor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Transit\WebBundle\Model\Deployment;

class DeploymentProcessorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Transit\WebBundle\Monolog\Processor\DeploymentProcessor');
    }

    function it_adds_the_deployment_id_to_the_log(Deployment $deployment)
    {
        $deployment->getId()->willReturn(47);

        $logRecord = [
            'level' => 100,
            'message' => 'deploying app...',
            'context' => [
                'deployment' => $deployment,
            ],
        ];

        $deploymentLogRecord = [
            'level' => 100,
            'message' => 'deploying app...',
            'context' => [
                'deployment' => $deployment,
            ],
            'deployment' => 47,
        ];

        $this->__invoke($logRecord)->shouldReturn($deploymentLogRecord);
    }
}
