<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassMethodsDefinedUnderKeywordsSniff
extends NetherCS\Sniffers\ScopeClassMethod {

	const
	FixReason       = 'NN: Class Methods must be defined under their keywords',
	MetricName      = 'Methods Defined Under Keywords',
	ResultIncorrect = 'Incorrect',
	ResultProper    = 'Proper';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$Whitespace = $this->GetContentFromStack($StackPtr+1);
		$Indent = NULL;

		if(trim($Whitespace," \r") !== "\n") {
			$Indent = $this->GetCurrentIndent($StackPtr);

			$this->BumpMetric(static::MetricName,static::ResultIncorrect);
			$this->SubmitFix(
				sprintf('%s (%s)',static::FixReason,$this->File->GetDeclarationName($StackPtr)),
				$Whitespace,
				"\n{$Indent}",
				($StackPtr+1)
			);
		}

		$this->BumpMetric(static::MetricName,static::ResultProper);
		return;
	}

}
