<?php

namespace Transit\UserBundle\Security;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface as FOSUserInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Transit\WebBundle\Model\OauthAccount;
use Transit\WebBundle\Model\UserAccount;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class UserProvider extends FOSUBUserProvider implements OAuthAwareUserProviderInterface
{
    /**
     * @var DocumentRepository
     */
    private $oauthAccountRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param UserManagerInterface $userManager
     * @param DocumentRepository     $oauthAccountRepository
     */
    public function __construct(UserManagerInterface $userManager, DocumentRepository $oauthAccountRepository, TokenStorageInterface $tokenStorage)
    {
        $this->userManager = $userManager;
        $this->oauthAccountRepository = $oauthAccountRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param UserResponseInterface $response
     *
     * @return FOSUserInterface|UserInterface
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $oauth = $this->oauthAccountRepository->findOneBy(array(
            'provider' => $response->getResourceOwner()->getName(),
            'identifier' => $response->getUsername()
        ));

        if ($oauth instanceof OauthAccount) {
            return $oauth->getUserAccount();
        }

        if (null !== $response->getEmail()) {
            $user = $this->findUser($response);

            if (null !== $user) {
                return $this->updateUserByOAuthUserResponse($user, $response);
            }
        }

        return $this->createUserByOAuthUserResponse($response);
    }

    /**
     * @param UserResponseInterface $response
     *
     * @return FOSUserInterface
     */
    private function findUser(UserResponseInterface $response)
    {
        $token = $this->tokenStorage->getToken();

        if ($token->isAuthenticated()) {
            return $token->getUser();
        }

        return $this->userManager->findUserByEmail($response->getEmail());
    }

    /**
     * Ad-hoc creation of user
     *
     * @param UserResponseInterface $response
     *
     * @return UserAccount
     */
    protected function createUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = $this->userManager->createUser();

        if (null !== $email = $response->getEmail()) {
            $user->setEmail($email);
        }

        if (!$user->getUsername()) {
            $user->setUsername($response->getNickname());
        }

        // set random password to prevent issue with not nullable field & potential security hole
        $user->setPlainPassword(substr(sha1($response->getAccessToken()), 0, 10));

        /**
         * @todo Approve users who sign up via OAuth.
         */
        $user->setEnabled(true);

        return $this->updateUserByOAuthUserResponse($user, $response);
    }

    /**
     * Attach OAuth sign-in provider account to existing user
     *
     * @param FOSUserInterface      $user
     * @param UserResponseInterface $response
     *
     * @return FOSUserInterface
     */
    protected function updateUserByOAuthUserResponse(FOSUserInterface $user, UserResponseInterface $response)
    {
        /** @var OauthAccount $oauth */
        $oauth = $this->oauthAccountRepository->createNew();
        $oauth->setIdentifier($response->getUsername());
        $oauth->setProvider($response->getResourceOwner()->getName());
        $oauth->setAccessToken($response->getAccessToken());
        $oauth->setNickname($response->getNickname());
        $oauth->setRealName($response->getRealName());
        $oauth->setProfilePicture($response->getProfilePicture());

        /** @var $user UserAccount */
        $user->addOAuthAccount($oauth);

        $this->userManager->updateUser($user);

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        /** @var $user FOSUserInterface */
        $this->updateUserByOAuthUserResponse($user, $response);
    }
}
