<?php

namespace Transit\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class TransitUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
