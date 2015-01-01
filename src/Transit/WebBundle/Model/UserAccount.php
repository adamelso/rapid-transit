<?php

namespace Transit\WebBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class UserAccount extends User
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var Project[]|Collection
     */
    protected $projects;

    /**
     * @var OauthAccount[]|Collection
     */
    protected $oauthAccounts;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->projects = new ArrayCollection();
        $this->oauthAccounts = new ArrayCollection();
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param Project $project
     *
     * @return $this
     */
    public function addProject(Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * @param Project $project
     *
     * @return $this
     */
    public function removeProject(Project $project)
    {
        $this->projects->removeElement($project);

        return $this;
    }

    /**
     * @return OauthAccount[]|Collection
     */
    public function getOauthAccounts()
    {
        return $this->oauthAccounts;
    }

    /**
     * @param OauthAccount $oauthAccount
     *
     * @return $this
     */
    public function addOauthAccount(OauthAccount $oauthAccount)
    {
        $oauthAccount->setUserAccount($this);

        $this->oauthAccounts[] = $oauthAccount;

        return $this;
    }

    /**
     * @param OauthAccount $oauthAccount
     *
     * @return $this
     */
    public function removeOauthAccount(OauthAccount $oauthAccount)
    {
        $this->oauthAccounts->removeElement($oauthAccount);

        return $this;
    }

    /**
     * @todo If the same GitHub OAuth account is added twice to a user as a result of renewed permissions, overwrite the previous one.
     *
     * @return null|OauthAccount
     */
    public function getGithubOauthAccount()
    {
        $githubOauth = null;

        foreach ($this->getOauthAccounts() as $oauth) {
            if ("github" === $oauth->getProvider()) {
                $githubOauth = $oauth;
            }
        }

        return $githubOauth;
    }
}
