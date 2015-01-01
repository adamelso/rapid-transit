<?php

namespace Transit\WebBundle;

use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class TransitWebBundle extends AbstractResourceBundle
{
    const TRANSIT_VERSION = '0.1.2-DEV';
    const TRANSIT_VERSION_ID = '00102';
    const TRANSIT_MAJOR_VERSION = '0';
    const TRANSIT_MINOR_VERSION = '1';
    const TRANSIT_RELEASE_VERSION = '2';
    const TRANSIT_EXTRA_VERSION = 'DEV';

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
