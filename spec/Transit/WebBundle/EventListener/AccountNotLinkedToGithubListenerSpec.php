<?php

namespace spec\Transit\WebBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\RouterInterface;
use Transit\WebBundle\Exception\AccountNotLinkedToGithubException;

class AccountNotLinkedToGithubListenerSpec extends ObjectBehavior
{
    function let(RouterInterface $router)
    {
        $this->beConstructedWith($router);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Transit\WebBundle\EventListener\AccountNotLinkedToGithubListener');
    }

    function it_ignores_irrelavent_exceptions(GetResponseForExceptionEvent $event, \Exception $anyOtherException, RouterInterface $router)
    {
        $event->getException()->willReturn($anyOtherException);

        $router->generate(Argument::any())->shouldNotBeCalled();
        $event->setResponse(Argument::any())->shouldNotBeCalled();

        $this->onKernelException($event);
    }

    function it_redirects_to_the_GitHub_account_connect_page_when_the_user_does_not_have_an_OAuth_connection_to_GitHub(
        GetResponseForExceptionEvent $event, AccountNotLinkedToGithubException $exception, RouterInterface $router
    ) {
        $event->getException()->willReturn($exception);

        $router->generate('hwi_oauth_service_redirect', ['service' => 'github'])->willReturn('/oauth/connect/github');

        $redirect = new RedirectResponse('/oauth/connect/github', RedirectResponse::HTTP_FOUND);
        $event->setResponse($redirect)->shouldBeCalled();

        $this->onKernelException($event);
    }
}
