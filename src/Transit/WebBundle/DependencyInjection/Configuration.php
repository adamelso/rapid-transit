<?php

namespace Transit\WebBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Default configuration:
     *
     *      transit_web:
     *          driver: doctrine/mongodb-odm
     *          classes:
     *              ssh_key_pair:
     *                  model:      Transit\WebBundle\Model\SshKeyPair
     *                  controller: Transit\WebBundle\Controller\SshKeyPairController
     *                  repository: Transit\WebBundle\Repository\SshKeyPairRepository
     *                  form:       Transit\WebBundle\Form\Type\SshKeyPairType
     *              project:
     *                  model:      Transit\WebBundle\Model\Project
     *                  controller: Transit\WebBundle\Controller\ProjectController
     *                  repository: Transit\WebBundle\Repository\ProjectRepository
     *                  form:       Transit\WebBundle\Form\Type\ProjectType
     *              hook:
     *                  model:      Transit\WebBundle\Model\Hook
     *                  controller: Transit\WebBundle\Controller\HookController
     *                  repository: Transit\WebBundle\Repository\HookRepository
     *                  form:       Transit\WebBundle\Form\Type\HookType
     *              deployment:
     *                  model:      Transit\WebBundle\Model\Deployment
     *                  controller: Transit\WebBundle\Controller\DeploymentController
     *                  repository: Transit\WebBundle\Repository\DeploymentRepository
     *                  form:       Transit\WebBundle\Form\Type\DeploymentType
     *          validation_groups:
     *               ssh_key_pair:  [ transit ]
     *               project:       [ transit ]
     *               hook:          [ transit ]
     *               deployment:    [ transit ]
     *          templates:
     *              ssh_key_pair:   "TransitWebBundle:SshKeyPair"
     *              project:        "TransitWebBundle:Project"
     *              hook:           "TransitWebBundle:Hook"
     *              deployment:     "TransitWebBundle:Deployment"
     *
     *
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('transit_web');

        $rootNode
            ->children()

                ->scalarNode('driver')
                    ->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM)
                ->end()

                ->arrayNode('classes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('ssh_key_pair')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Transit\WebBundle\Model\SshKeyPair')->end()
                                ->scalarNode('controller')->defaultValue('Transit\WebBundle\Controller\SshKeyPairController')->end()
                                ->scalarNode('repository')->defaultValue('Transit\WebBundle\Repository\SshKeyPairRepository')->end()
                                ->scalarNode('form')->defaultValue('Transit\WebBundle\Form\Type\SshKeyPairType')->end()
                            ->end()
                        ->end()
                        ->arrayNode('project')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Transit\WebBundle\Model\Project')->end()
                                ->scalarNode('controller')->defaultValue('Transit\WebBundle\Controller\ProjectController')->end()
                                ->scalarNode('repository')->defaultValue('Transit\WebBundle\Repository\ProjectRepository')->end()
                                ->scalarNode('form')->defaultValue('Transit\WebBundle\Form\Type\ProjectType')->end()
                            ->end()
                        ->end()
                        ->arrayNode('hook')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Transit\WebBundle\Model\Hook')->end()
                                ->scalarNode('controller')->defaultValue('Transit\WebBundle\Controller\HookController')->end()
                                ->scalarNode('repository')->defaultValue('Transit\WebBundle\Repository\HookRepository')->end()
                                ->scalarNode('form')->defaultValue('Transit\WebBundle\Form\Type\HookType')->end()
                            ->end()
                        ->end()
                        ->arrayNode('deployment')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Transit\WebBundle\Model\Deployment')->end()
                                ->scalarNode('controller')->defaultValue('Transit\WebBundle\Controller\DeploymentController')->end()
                                ->scalarNode('repository')->defaultValue('Transit\WebBundle\Repository\DeploymentRepository')->end()
                                ->scalarNode('form')->defaultValue('Transit\WebBundle\Form\Type\DeploymentType')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('validation_groups')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('ssh_key_pair')
                            ->prototype('scalar')->end()
                            ->defaultValue(['transit'])
                        ->end()
                        ->arrayNode('project')
                            ->prototype('scalar')->end()
                            ->defaultValue(['transit'])
                        ->end()
                        ->arrayNode('hook')
                            ->prototype('scalar')->end()
                            ->defaultValue(['transit'])
                        ->end()
                        ->arrayNode('deployment')
                            ->prototype('scalar')->end()
                            ->defaultValue(['transit'])
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('ssh_key_pair')->defaultValue('TransitWebBundle:SshKeyPair')->end()
                        ->scalarNode('project')->defaultValue('TransitWebBundle:Project')->end()
                        ->scalarNode('hook')->defaultValue('TransitWebBundle:Hook')->end()
                        ->scalarNode('deployment')->defaultValue('TransitWebBundle:Deployment')->end()
                    ->end()
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
