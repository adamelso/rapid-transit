<?php

namespace spec\Transit\WebBundle\Github;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Transit\WebBundle\Github\Repository;

class GithubAccountSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedThrough('createWithOauthToken', ['abcdef123', $client]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Transit\WebBundle\Github\GithubAccount');
    }

    function it_fetches_all_the_accessible_repositories_for_the_user(
        Client $client,
        Request $userReposRequest, Response $userReposResponse,
        Request $userOrgsRequest, Response $userOrgsResponse,
        Request $orgReposRequest, Response $orgReposResponse
    ) {
        // User Repositories

        $client->createRequest('GET', '/user/repos', [
            'query' => [
                'sort' => 'name',
                'type' => 'all',
                'access_token' => 'abcdef123',
            ]
        ])->willReturn($userReposRequest);

        $client->send($userReposRequest)->willReturn($userReposResponse);

        $userReposResponse->json()->willReturn([
            [
                'full_name' => 'adamelso/rapid-transit',
                'html_url' => 'https://github.com/adamelso/rapid-transit',
                'ssh_url' => 'git@github.com:adamelso/rapid-transit.git',
            ]
        ]);

        // User Organizations

        $client->createRequest('GET', '/user/orgs', [
            'query' => [
                'access_token' => 'abcdef123',
            ]
        ])->willReturn($userOrgsRequest);

        $client->send($userOrgsRequest)->willReturn($userOrgsResponse);

        $userOrgsResponse->json()->willReturn([
            [
                'repos_url' => 'https://api.github.com/orgs/archfizz/repos',
            ]
        ]);

        // User Organizations' Repositories

        $client->createRequest('GET', 'https://api.github.com/orgs/archfizz/repos', [
            'query' => [
                'type' => 'all',
                'access_token' => 'abcdef123',
            ]
        ])->willReturn($orgReposRequest);

        $client->send($orgReposRequest)->willReturn($orgReposResponse);

        $orgReposResponse->json()->willReturn([
            [
                'full_name' => 'archfizz/phpairplay',
                'html_url' => 'https://github.com/archfizz/phpairplay',
                'ssh_url' => 'git@github.com:archfizz/phpairplay.git',
            ]
        ]);

        // All

        $userRepo = new Repository();

        $userRepo
            ->setFullName('adamelso/rapid-transit')
            ->setHtmlUrl('https://github.com/adamelso/rapid-transit')
            ->setSshUrl('git@github.com:adamelso/rapid-transit.git')
        ;

        $orgRepo = new Repository();

        $orgRepo
            ->setFullName('archfizz/phpairplay')
            ->setHtmlUrl('https://github.com/archfizz/phpairplay')
            ->setSshUrl('git@github.com:archfizz/phpairplay.git')
        ;


        $this->getAccessibleRepositories()->shouldBeLike([$userRepo, $orgRepo]);
    }
}
