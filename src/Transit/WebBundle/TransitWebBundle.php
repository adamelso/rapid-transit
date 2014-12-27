<?php

namespace Transit\WebBundle;

use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class TransitWebBundle extends AbstractResourceBundle
{
    /**
     * {@inheritdoc}
     */
    protected function getBundlePrefix()
    {
        return 'transit_web';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSupportedDrivers()
    {
        return [
            //SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
            SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelNamespace()
    {
        return __NAMESPACE__ . '\Model';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDoctrineMappingDirectory()
    {
        return 'model';
    }
}
