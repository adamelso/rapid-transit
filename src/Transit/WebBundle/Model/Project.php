<?php

namespace Transit\WebBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Project
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $repositoryUrl;

    /**
     * @var UserAccount
     */
    protected $owner;

    /**
     * @var Collection|Deployment[]
     */
    protected $deployments;

    public function __construct()
    {
        $this->deployments = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepositoryUrl()
    {
        return $this->repositoryUrl;
    }

    /**
     * @param string $repositoryUrl
     *
     * @return $this
     */
    public function setRepositoryUrl($repositoryUrl)
    {
        $this->repositoryUrl = $repositoryUrl;

        return $this;
    }

    /**
     * @return UserAccount
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param UserAccount $owner
     *
     * @return $this
     */
    public function setOwner(UserAccount $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Deployment[]
     */
    public function getDeployments()
    {
        return $this->deployments;
    }

    /**
     * @param Deployment $deployment
     *
     * @return $this
     */
    public function addDeployment(Deployment $deployment)
    {
        $this->deployments[] = $deployment;

        return $this;
    }
}
