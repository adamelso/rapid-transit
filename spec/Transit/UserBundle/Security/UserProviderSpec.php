<?php

namespace spec\Transit\UserBundle\Security;

use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Transit\WebBundle\Model\OauthAccount;
use Transit\WebBundle\Model\UserAccount;

class UserProviderSpec extends ObjectBehavior
{
    function let(UserManagerInterface $userManager, DocumentRepository $oauthAccountRepository, TokenStorage $tokenStorage, AnonymousToken $anonymousToken)
    {
        $tokenStorage->getToken()->willReturn($anonymousToken);
        $anonymousToken->isAuthenticated()->willReturn(false);
        $anonymousToken->getUser()->shouldNotBeCalled();

        $this->beConstructedWith($userManager, $oauthAccountRepository, $tokenStorage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Transit\UserBundle\Security\UserProvider');
    }

    function it_implements_the_HWI_OAuth_aware_user_provider_interface()
    {
        $this->shouldImplement('HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface');
    }

    function it_should_connect_an_OAuth_account_with_a_given_user(
        UserManagerInterface $userManager, DocumentRepository $oauthAccountRepository, UserAccount $user, UserResponseInterface $response, ResourceOwnerInterface $resourceOwner, OauthAccount $oauth
    ) {
        $resourceOwner->getName()->willReturn('github');

        $response->getUsername()->willReturn('username');
        $response->getNickname()->willReturn('user');
        $response->getRealName()->willReturn('John Doh');
        $response->getProfilePicture()->willReturn('http://example.com/pic.jpg');
        $response->getResourceOwner()->willReturn($resourceOwner);
        $response->getAccessToken()->willReturn('access_token');

        $oauthAccountRepository->createNew()->willReturn($oauth);

        $oauth->setIdentifier('username')->shouldBeCalled();
        $oauth->setProvider('github')->shouldBeCalled();
        $oauth->setAccessToken('access_token')->shouldBeCalled();
        $oauth->setNickname('user')->shouldBeCalled();
        $oauth->setRealName('John Doh')->shouldBeCalled();
        $oauth->setProfilePicture('http://example.com/pic.jpg')->shouldBeCalled();

        $user->addOauthAccount($oauth)->shouldBeCalled();

        $userManager->updateUser($user)->shouldBeCalled();

        $this->connect($user, $response);
    }

    function it_returns_the_user_if_relation_exists(
        DocumentRepository $oauthAccountRepository, OAuthUser $user, UserResponseInterface $response, ResourceOwnerInterface $resourceOwner, OauthAccount $oauth
    ) {
        $resourceOwner->getName()->willReturn('github');

        $response->getEmail()->willReturn('username@email');
        $response->getUsername()->willReturn('username');
        $response->getNickname()->willReturn('user');
        $response->getRealName()->willReturn('John Doh');
        $response->getProfilePicture()->willReturn('http://example.com/pic.jpg');
        $response->getResourceOwner()->willReturn($resourceOwner);

        $oauthAccountRepository->findOneBy(array('provider' => 'github', 'identifier' => 'username'))->willReturn($oauth);

        $oauth->getUserAccount()->willReturn($user);

        $this->loadUserByOAuthUserResponse($response)->shouldReturn($user);
    }

    function it_should_update_the_user_when_the_user_was_found_by_email(
        UserManagerInterface $userManager, DocumentRepository $oauthAccountRepository, UserAccount $user, UserResponseInterface $response, ResourceOwnerInterface $resourceOwner, OauthAccount $oauth
    ) {
        $resourceOwner->getName()->willReturn('github');

        $response->getEmail()->willReturn('username@email');
        $response->getUsername()->willReturn('username');
        $response->getNickname()->willReturn('user');
        $response->getRealName()->willReturn('John Doh');
        $response->getProfilePicture()->willReturn('http://example.com/pic.jpg');
        $response->getResourceOwner()->willReturn($resourceOwner);
        $response->getAccessToken()->willReturn('access_token');

        $oauthAccountRepository->findOneBy(array('provider' => 'github', 'identifier' => 'username'))->willReturn(null);
        $userManager->findUserByEmail('username@email')->willReturn($user);

        $oauthAccountRepository->createNew()->willReturn($oauth);

        $oauth->setIdentifier('username');
        $oauth->setProvider('github');
        $oauth->setAccessToken('access_token');
        $oauth->setNickname('user');
        $oauth->setRealName('John Doh');
        $oauth->setProfilePicture('http://example.com/pic.jpg');

        $user->addOauthAccount($oauth)->shouldBeCalled();

        $userManager->updateUser($user)->shouldBeCalled();

        $this->loadUserByOAuthUserResponse($response)->shouldReturn($user);
    }

    function it_should_update_the_user_when_the_user_was_found_by_email_and_already_authenticated(
        UserManagerInterface $userManager, DocumentRepository $oauthAccountRepository, UserAccount $user, UserResponseInterface $response, ResourceOwnerInterface $resourceOwner, OauthAccount $oauth, TokenStorageInterface $tokenStorage, TokenInterface $token
    ) {
        $tokenStorage->getToken()->willReturn($token);
        $token->isAuthenticated()->willReturn(true);

        $resourceOwner->getName()->willReturn('github');

        $response->getEmail()->willReturn('username@email');
        $response->getUsername()->willReturn('username');
        $response->getNickname()->willReturn('user');
        $response->getRealName()->willReturn('John Doh');
        $response->getProfilePicture()->willReturn('http://example.com/pic.jpg');
        $response->getResourceOwner()->willReturn($resourceOwner);
        $response->getAccessToken()->willReturn('access_token');

        $oauthAccountRepository->findOneBy(array('provider' => 'github', 'identifier' => 'username'))->willReturn(null);
        $userManager->findUserByEmail(Argument::any())->shouldNotBeCalled();

        $token->getUser()->willReturn($user);

        $oauthAccountRepository->createNew()->willReturn($oauth);

        $oauth->setIdentifier('username');
        $oauth->setProvider('github');
        $oauth->setAccessToken('access_token');
        $oauth->setNickname('user');
        $oauth->setRealName('John Doh');
        $oauth->setProfilePicture('http://example.com/pic.jpg');

        $user->addOauthAccount($oauth)->shouldBeCalled();

        $userManager->updateUser($user)->shouldBeCalled();

        $this->loadUserByOAuthUserResponse($response)->shouldReturn($user);
    }

    function it_should_create_a_new_user_when_none_was_found(
        UserManagerInterface $userManager, DocumentRepository $oauthAccountRepository, UserAccount $user, UserResponseInterface $response, ResourceOwnerInterface $resourceOwner, OauthAccount $oauth
    ) {
        $resourceOwner->getName()->willReturn('github');

        $response->getEmail()->willReturn(null);
        $response->getUsername()->willReturn('username');
        $response->getNickname()->willReturn('user');
        $response->getRealName()->willReturn('John Doh');
        $response->getProfilePicture()->willReturn('http://example.com/pic.jpg');
        $response->getResourceOwner()->willReturn($resourceOwner);
        $response->getAccessToken()->willReturn('access_token');

        $oauthAccountRepository->findOneBy(array('provider' => 'github', 'identifier' => 'username'))->willReturn(null);
        $oauthAccountRepository->createNew()->willReturn($oauth);

        $userManager->createUser()->willReturn($user);

        $oauth->setIdentifier('username');
        $oauth->setProvider('github');
        $oauth->setAccessToken('access_token');
        $oauth->setNickname('user');
        $oauth->setRealName('John Doh');
        $oauth->setProfilePicture('http://example.com/pic.jpg');

        $user->getUsername()->willReturn(null);
        $user->setUsername('user')->shouldBeCalled();
        $user->setPlainPassword('2ff2dfe363')->shouldBeCalled();
        $user->setEnabled(true)->shouldBeCalled();
        $user->addOauthAccount($oauth)->shouldBeCalled();

        $userManager->updateUser($user)->shouldBeCalled();

        $this->loadUserByOAuthUserResponse($response)->shouldReturn($user);
    }
}
