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
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->projects = new ArrayCollection();
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
}
