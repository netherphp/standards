<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassMethodsDefinedUnderKeywordsSniff
extends NetherCS\SniffClassMethodTemplate {

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
		$Find = [ T_STATIC, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_WHITESPACE, T_ABSTRACT, T_FINAL ];
		$Start = NULL;
		$Indent = NULL;

		if(trim($Whitespace," \r") !== "\n") {
			$Start = $this->File->FindPrevious($Find,($StackPtr-1),NULL,TRUE) + 1;

			while($Start <= $StackPtr) {
				$Indent = $this->GetContentFromStack($Start++);
		
				if(preg_match('/^[ \t]$/',$Indent))
				break;
			}

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
