<?php
namespace AUS\GrumPHPXliffTask;

use GrumPHP\Linter\LintError;
use GrumPHP\Linter\Xml\XmlLinter;
use SplFileInfo;

class XliffLinter extends XmlLinter
{
    public function lint(SplFileInfo $file)
    {
        $lintErrors = parent::lint($file);
        if ($lintErrors->count()) {
            return $lintErrors;
        }
        $document = new \DOMDocument();
        if (!$document->load($file->getPathname())) {
            $lintErrors->add(new LintError(LintError::TYPE_FATAL, 'something realy strange happend file could not be loaded a second time', $file->__toString(), 0));
            return $lintErrors;
        }
        
        $rootElement = $document->documentElement;
        /** @var \DOMElement[] $fileTags */
        $fileTags = $rootElement->getElementsByTagName('file');
        foreach ($fileTags as $fileTag) {
            if ($fileTag->attributes->getNamedItem('target')) {
                $lintErrors->add(new LintError(LintError::TYPE_ERROR, 'file tag has a "target" attribute that is deprecated please use "target-language" as default', $file->__toString(), $fileTag->getLineNo()));
                continue;
            }
            if ($fileTag->attributes->getNamedItem('target-language')) {
                /** @var \DOMElement[] $transUnitTags */
                $transUnitTags = $fileTag->getElementsByTagName('trans-unit');
                foreach ($transUnitTags as $transUnitTag) {
                    if (!$transUnitTag->attributes->getNamedItem('id')) {
                        $lintErrors->add(new LintError(LintError::TYPE_ERROR, 'trans-unit tag has no "id" tag', $file->__toString(), $transUnitTag->getLineNo()));
                    }
                    if (!$transUnitTag->getElementsByTagName('target')->length) {
                        $lintErrors->add(new LintError(LintError::TYPE_ERROR, 'trans-unit tag has no "target" child Node', $file->__toString(), $transUnitTag->getLineNo()));
                    }
                }
            } else {
                /** @var \DOMElement[] $transUnitTags */
                $transUnitTags = $fileTag->getElementsByTagName('trans-unit');
                foreach ($transUnitTags as $transUnitTag) {
                    if (!$transUnitTag->attributes->getNamedItem('id')) {
                        $lintErrors->add(new LintError(LintError::TYPE_ERROR, 'trans-unit tag has no "id" tag', $file->__toString(), $transUnitTag->getLineNo()));
                    }
                    if (!$transUnitTag->getElementsByTagName('source')->length) {
                        $lintErrors->add(new LintError(LintError::TYPE_ERROR, 'trans-unit tag has no "source" child Node', $file->__toString(), $transUnitTag->getLineNo()));
                    }
                    if ($targetElement = $transUnitTag->getElementsByTagName('target')->item(0)) {
                        $lintErrors->add(new LintError(LintError::TYPE_WARNING, 'trans-unit tag has "target" child Node', $file->__toString(), $targetElement->getLineNo()));
                    }
                }
            }
        }
        
        return $lintErrors;
    }
}
