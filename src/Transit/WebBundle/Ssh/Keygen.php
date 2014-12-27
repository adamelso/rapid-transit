<?php

namespace Transit\WebBundle\Ssh;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Keygen
{
    /**
     * @var \Crypt_RSA
     */
    private $rsa;

    /**
     * @param \Crypt_RSA $rsa
     */
    public function __construct(\Crypt_RSA $rsa)
    {
        $this->rsa = $rsa;
    }

    /**
     * @return array
     */
    public function generate()
    {
        $this->rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);

        return $this->rsa->createKey();
    }
}
