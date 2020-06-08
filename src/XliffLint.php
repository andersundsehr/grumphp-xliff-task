<?php

namespace PLUS\GrumPHPXliffTask;

use GrumPHP\Exception\RuntimeException;
use GrumPHP\Runner\TaskResult;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Task\AbstractLinterTask;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

class XliffLint extends AbstractLinterTask
{
    /** @var XliffLinter */
    protected $linter;

    public static function getConfigurableOptions(): OptionsResolver
    {
        $resolver = parent::getConfigurableOptions();
        $resolver->setDefaults(
            [
                'load_from_net' => false,
                'x_include' => false,
                'dtd_validation' => false,
                'scheme_validation' => false,
                'triggered_by' => ['xlf'],
            ]
        );
        $resolver->addAllowedTypes('load_from_net', ['bool']);
        $resolver->addAllowedTypes('x_include', ['bool']);
        $resolver->addAllowedTypes('dtd_validation', ['bool']);
        $resolver->addAllowedTypes('scheme_validation', ['bool']);
        $resolver->addAllowedTypes('triggered_by', ['array']);
        return $resolver;
    }

    public function canRunInContext(ContextInterface $context): bool
    {
        return ($context instanceof GitPreCommitContext || $context instanceof RunContext);
    }

    public function run(ContextInterface $context): TaskResultInterface
    {
        $options = $this->getConfig()->getOptions();
        $files = $context->getFiles()->extensions($options['triggered_by']);
        if (0 === count($files)) {
            return TaskResult::createSkipped($this, $context);
        }
        $this->linter->setLoadFromNet($options['load_from_net']);
        $this->linter->setXInclude($options['x_include']);
        $this->linter->setDtdValidation($options['dtd_validation']);
        $this->linter->setSchemeValidation($options['scheme_validation']);
        try {
            $lintErrors = $this->lint($files);
        } catch (RuntimeException $e) {
            return TaskResult::createFailed($this, $context, $e->getMessage());
        }
        if ($lintErrors->count()) {
            return TaskResult::createFailed($this, $context, (string)$lintErrors);
        }
        return TaskResult::createPassed($this, $context);
    }
}
