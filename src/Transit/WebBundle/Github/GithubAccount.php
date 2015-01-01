<?php

namespace Transit\WebBundle\Github;

use GuzzleHttp\Client;
use GuzzleHttp\Message\RequestInterface;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class GithubAccount implements Account
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $oauthToken;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $oauthToken
     * @param Client $client
     *
     * @return static
     */
    public static function createWithOauthToken($oauthToken, Client $client)
    {
        $account = new static($client);
        $account->setOauthToken($oauthToken);

        return $account;
    }

    /**
     * @param string $oauthToken
     */
    public function setOauthToken($oauthToken)
    {
        $this->oauthToken = $oauthToken;
    }

    /**
     * @return array|Repository[]
     */
    public function getAccessibleRepositories()
    {
        $repositoriesData = $this->fetchRepositories();

        $repositories = [];

        foreach ($repositoriesData as $repo) {
            $repository = new Repository();

            $repository
                ->setFullName($repo['full_name'])
                ->setHtmlUrl($repo['html_url'])
                ->setSshUrl($repo['ssh_url'])
            ;


            $repositories[] = $repository;
        }

        return $repositories;
    }

    /**
     * @return array
     */
    private function fetchRepositories()
    {
        $data = $this->client->send($this->createUserRepositoriesRequest())->json();
        $orgs = $this->client->send($this->createUserOrganizationRequest())->json();

        foreach ($orgs as $org) {
            $data = array_merge($data, $this->client->send($this->createUserOrganizationRepositoriesRequest($org))->json());
        }

        return $data;
    }

    /**
     * @return RequestInterface
     */
    private function createUserRepositoriesRequest()
    {
        return $this->client->createRequest('GET', '/user/repos', [
            'query' => [
                'sort' => 'name',
                'type' => 'all',
                'access_token' => $this->oauthToken,
            ]
        ]);
    }

    /**
     * @return RequestInterface
     */
    private function createUserOrganizationRequest()
    {
        return $this->client->createRequest('GET', '/user/orgs', [
            'query' => [
                'access_token' => $this->oauthToken,
            ]
        ]);
    }

    /**
     * @param array $org
     *
     * @return RequestInterface
     */
    private function createUserOrganizationRepositoriesRequest(array $org)
    {
        return $this->client->createRequest('GET', $org['repos_url'], [
            'query' => [
                'type' => 'all',
                'access_token' => $this->oauthToken,
            ]
        ]);
    }
}
