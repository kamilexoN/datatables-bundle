<?php

/*
 * Symfony DataTables Bundle
 * (c) Omines Internetbureau B.V. - https://omines.nl/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Omines\DataTablesBundle\DependencyInjection;

use Omines\DataTablesBundle\Adapter\AdapterInterface;
use Omines\DataTablesBundle\Column\AbstractColumn;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Omines\DataTablesBundle\Filter\AbstractFilter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * DataTablesExtension.
 *
 * @author Niels Keurentjes <niels.keurentjes@omines.com>
 */
class DataTablesExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->setAlias('datatables.renderer', $config['renderer']);
        unset($config['renderer']);

        $container->setParameter('datatables.config', $config);

        $container->registerForAutoconfiguration(AbstractColumn::class)
            ->addTag('datatables.column')
            ->setShared(false);
        $container->registerForAutoconfiguration(AbstractFilter::class)
            ->addTag('datatables.filter')
            ->setShared(false);
        $container->registerForAutoconfiguration(AdapterInterface::class)
            ->addTag('datatables.adapter')
            ->setShared(false);
        $container->registerForAutoconfiguration(DataTableTypeInterface::class)
            ->addTag('datatables.type');
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        // Default would underscore the camelcase unintuitively
        return 'datatables';
    }
}
