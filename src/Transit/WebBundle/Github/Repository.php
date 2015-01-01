<?php

namespace Transit\WebBundle\Github;

/**
 * GitHub hosted code repository.
 *
 * Not to be confused with a repository in Database Abstraction.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Repository
{
    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     */
    private $htmlUrl;

    /**
     * @var string
     */
    private $sshUrl;

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlUrl()
    {
        return $this->htmlUrl;
    }

    /**
     * @param string $htmlUrl
     *
     * @return $this
     */
    public function setHtmlUrl($htmlUrl)
    {
        $this->htmlUrl = $htmlUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getSshUrl()
    {
        return $this->sshUrl;
    }

    /**
     * @param string $sshUrl
     *
     * @return $this
     */
    public function setSshUrl($sshUrl)
    {
        $this->sshUrl = $sshUrl;

        return $this;
    }
}
