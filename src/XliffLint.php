<?php
namespace PLUS\GrumPHPXliffTask;

use GrumPHP\Exception\RuntimeException;
use GrumPHP\Runner\TaskResult;
use GrumPHP\Task\AbstractLinterTask;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;

/**
 * Class XmlLint
 *
 * @package GrumPHP\Task
 */
class XliffLint extends AbstractLinterTask
{
    /**
     * @var XliffLinter
     */
    protected $linter;

    /**
     * @return string
     */
    public function getName()
    {
        return 'xlifflint';
    }

    /**
     * @return \Symfony\Component\OptionsResolver\OptionsResolver
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     * @throws \Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
     */
    public function getConfigurableOptions()
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

    /**
     * {@inheritdoc}
     */
    public function canRunInContext(ContextInterface $context)
    {
        return ($context instanceof GitPreCommitContext || $context instanceof RunContext);
    }

    /**
     * {@inheritdoc}
     */
    public function run(ContextInterface $context)
    {
        $config = $this->getConfiguration();
        $files = $context->getFiles()->extensions($config['triggered_by']);
        if (0 === count($files)) {
            return TaskResult::createSkipped($this, $context);
        }
        $this->linter->setLoadFromNet($config['load_from_net']);
        $this->linter->setXInclude($config['x_include']);
        $this->linter->setDtdValidation($config['dtd_validation']);
        $this->linter->setSchemeValidation($config['scheme_validation']);
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
