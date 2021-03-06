<?php

namespace nacholibre\BraintreeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Braintree_Configuration;

use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class nacholibreBraintreeExtension extends Extension implements PrependExtensionInterface
{
    protected $formTypeTemplate = 'nacholibreBraintreeBundle::fields.html.twig';

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (isset($config['environment'])) {
            $container->setParameter('comet_cult_braintree.environment', $config['environment']);
            Braintree_Configuration::environment($config['environment']);
        }
        if (isset($config['merchant_id'])) {
            $container->setParameter('comet_cult_braintree.merchant_id', $config['merchant_id']);
            Braintree_Configuration::merchantId($config['merchant_id']);
        }
        if (isset($config['public_key'])) {
            $container->setParameter('comet_cult_braintree.public_key', $config['public_key']);
            Braintree_Configuration::publicKey($config['public_key']);
        }
        if (isset($config['private_key'])) {
            $container->setParameter('comet_cult_braintree.private_key', $config['private_key']);
            Braintree_Configuration::privateKey($config['private_key']);
        }
    }

    public function prepend(ContainerBuilder $container) {
        //$configs = $container->getExtensionConfig($this->getAlias());
        //$this->processConfiguration(new Configuration(), $configs);
        $this->configureTwigBundle($container);
    }

    protected function configureTwigBundle(ContainerBuilder $container) {
        foreach (array_keys($container->getExtensions()) as $name) {
            switch ($name) {
                case 'twig':
                    $container->prependExtensionConfig(
                        $name,
                        array('form_themes' => array($this->formTypeTemplate))
                    );
                    break;
            }
        }
    }
}
