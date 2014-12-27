<?php

namespace Transit\WebBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class HookType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @todo Register `HookServiceType` as a service.
         */
        $builder->add('service', new HookServiceType());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'transit_hook';
    }
}
