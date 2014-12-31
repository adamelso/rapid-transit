<?php

namespace Transit\WebBundle\Model;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Deployment
{
    const QUEUED = 'queued';
    const IN_PROGRESS = 'in_progress';
    const FAILED = 'failed';
    const SUCCESSFUL = 'successful';

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var int
     */
    protected $number;

    /**
     * @var string
     */
    protected $commit;

    /**
     * @var string
     */
    protected $branch = 'master';

    /**
     * @var string
     */
    protected $status = self::QUEUED;

    /**
     * @var Project
     */
    protected $project;

    /**
     * Read-only.
     *
     * @var DeploymentLog[]
     */
    protected $logs;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getProject()->getName();
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
    public function getCommit()
    {
        return $this->commit;
    }

    /**
     * @param string $commit
     */
    public function setCommit($commit)
    {
        $this->commit = $commit;
    }

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param string $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return in_array($this->status, self::FAILED, self::SUCCESSFUL);
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return self::SUCCESSFUL === $this->status;
    }

    /**
     * @return bool
     */
    public function isQueued()
    {
        return self::QUEUED === $this->status;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return DeploymentLog[]
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }
}
