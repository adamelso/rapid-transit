<?php

namespace Transit\UserBundle\Security;

use Doctrine\Common\Persistence\ObjectRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface as FOSUserInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Transit\WebBundle\Model\OauthAccount;
use Transit\WebBundle\Model\UserAccount;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class UserProvider extends FOSUBUserProvider implements OAuthAwareUserProviderInterface
{
    /**
     * @var ObjectRepository
     */
    private $oauthAccountRepository;

    /**
     * @param UserManagerInterface $userManager
     * @param ObjectRepository     $oauthAccountRepository
     */
    public function __construct(UserManagerInterface $userManager, ObjectRepository $oauthAccountRepository)
    {
        $this->userManager = $userManager;
        $this->oauthAccountRepository = $oauthAccountRepository;
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
            $user = $this->userManager->findUserByEmail($response->getEmail());
            if (null !== $user) {
                return $this->updateUserByOAuthUserResponse($user, $response);
            }
        }

        return $this->createUserByOAuthUserResponse($response);
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
        $oauth = new OauthAccount(); // $this->oauthRepository->createNew();
        $oauth->setIdentifier($response->getUsername());
        $oauth->setProvider($response->getResourceOwner()->getName());
        $oauth->setAccessToken($response->getAccessToken());

        /* @var $user UserAccount */
        $user->addOAuthAccount($oauth);
        $oauth->setUserAccount($user);

        $this->userManager->updateUser($user);

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        /* @var $user FOSUserInterface */
        $this->updateUserByOAuthUserResponse($user, $response);
    }
}
