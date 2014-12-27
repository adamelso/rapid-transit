<?php

namespace Transit\WebBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class ProjectType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('repositoryUrl');
        $builder->add('submit', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'transit_project';
    }
}
