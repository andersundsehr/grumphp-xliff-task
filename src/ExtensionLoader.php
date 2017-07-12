<?php

namespace PLUS\GrumPHPXliffTask;

use GrumPHP\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ExtensionLoader
 *
 * @author Matthias Vogel <matthias.vogel@pluswerk.ag>
 * @package PLUS\GrumphpBomTask
 */
class ExtensionLoader implements ExtensionInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return \Symfony\Component\DependencyInjection\Definition
     * @throws \Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\BadMethodCallException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function load(ContainerBuilder $container)
    {
        $container->register('linter.xlifflint', XliffLinter::class);
        return $container->register('task.xlifflint', XliffLint::class)
            ->addArgument($container->get('config'))
            ->addArgument($container->get('linter.xlifflint'))
            ->addArgument($container->get('process_builder'))
            ->addArgument($container->get('formatter.raw_process'))
            ->addTag('grumphp.task', ['config' => 'xlifflint']);
    }
}
