<?php

namespace Transit\WebBundle\Github;

/**
 * Classes that will make request to the GitHub API for an authenticated user or otherwise,
 * should implement this interface.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
interface Account
{
    /**
     * @return array
     */
    public function getAccessibleRepositories();
}
