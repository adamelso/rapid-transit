<?php

namespace Transit\WebBundle\Integration;

use GuzzleHttp\Client;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Transit\WebBundle\Exception\AccountNotLinkedToGithubException;
use Transit\WebBundle\Github\GithubAccount;
use Transit\WebBundle\Model\OauthAccount;
use Transit\WebBundle\Model\UserAccount;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class GithubAccountFactory
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage, Client $client)
    {
        $this->tokenStorage = $tokenStorage;
        $this->client = $client;
    }

    /**
     * @return GithubAccount
     */
    public function createWithCurrentAuthenticatedUser()
    {
        return GithubAccount::createWithOauthToken($this->getOauthAccount()->getAccessToken(), $this->client);
    }

    /**
     * @return OauthAccount
     *
     * @throws \DomainException                  If the authenticated user is not a Transit User implementation with associated OAuth accounts.
     * @throws AccountNotLinkedToGithubException If the authenticated user has not connect their account to GitHub.
     */
    private function getOauthAccount()
    {
        /** @var UserAccount $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (! $user instanceof UserAccount) {
            throw new \DomainException('The model representing the authenticated user must be instance of Transit\WebBundle\Model\UserAccount.');
        }

        $githubOauthAccount = $user->getGithubOauthAccount();

        if (! $githubOauthAccount instanceof OauthAccount) {
            throw new AccountNotLinkedToGithubException("User account is not connected to GitHub.");
        }

        return $githubOauthAccount;
    }
}
