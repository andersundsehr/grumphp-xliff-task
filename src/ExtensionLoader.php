<?php

namespace PLUS\GrumPHPXliffTask;

use GrumPHP\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ExtensionLoader implements ExtensionInterface
{
    public function load(ContainerBuilder $container)
    {
        $container->register('linter.xlifflint', XliffLinter::class);
        return $container->register('task.xlifflint', XliffLint::class)
            ->addArgument(new Reference('linter.xlifflint'))
            ->addArgument(new Reference('process_builder'))
            ->addArgument(new Reference('formatter.raw_process'))
            ->addTag('grumphp.task', ['task' => 'xlifflint']);
    }
}
