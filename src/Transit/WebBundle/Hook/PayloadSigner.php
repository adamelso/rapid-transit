<?php

namespace Transit\WebBundle\Hook;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class PayloadSigner
{
    /**
     * @var string
     */
    private $secret;

    /**
     * @param string $secret
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param string $payload
     *
     * @return string
     */
    public function sign($payload)
    {
        return hash_hmac('sha1', $payload, $this->secret, false);
    }
}
