<?php

namespace Transit\WebBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class ProjectController extends ResourceController
{
    /**
     * Provides quick adding of projects by using data from GitHub.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function importAction(Request $request)
    {
        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('import.html'))
            ->setData([
                'repositories' => $this->get('transit.provider.account.github')->getAccessibleRepositories(),
            ])
        ;

        return $this->handleView($view);
    }
}
