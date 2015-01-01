<?php

namespace Transit\WebBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\RouterInterface;
use Transit\WebBundle\Exception\AccountNotLinkedToGithubException;

class AccountNotLinkedToGithubListener
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();

        if (! $e instanceof AccountNotLinkedToGithubException) {
            return;
        }

        $event->setResponse(new RedirectResponse($this->router->generate('hwi_oauth_service_redirect', ['service' => 'github'])));
    }
}
