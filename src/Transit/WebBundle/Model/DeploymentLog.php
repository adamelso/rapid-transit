<?php

namespace Transit\WebBundle\Model;

class DeploymentLog
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var Deployment
     */
    protected $deployment;

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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return Deployment
     */
    public function getDeployment()
    {
        return $this->deployment;
    }

    /**
     * @param Deployment $deployment
     */
    public function setDeployment(Deployment $deployment)
    {
        $this->deployment = $deployment;
    }
}
