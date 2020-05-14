<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;
use \PHP_CodeSniffer as PHPCS;

class FunctionNamesPascalCaseSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method/Function names must be PascalCased (%s)',
	FixUpdate = 'NN: Update internal use of altered Method Names (%s)';

	public function
	Execute():
	Void {

		// T_FUNCTION T_WHITESPACE T_STRING
		// This       +1          +2

		$StackPtr = $this->StackPtr;
		$Type = T_FUNCTION;
		$Current = NULL;
		$Expected = NULL;

		// in these contexts it seems we can actually have more than one
		// whitespace token in a row due to a newline.

		while($Type && $Type !== T_STRING) {
			$Type = $this->GetTypeFromStack(++$StackPtr);
		}

		$Current = $this->GetContentFromStack($StackPtr);
		$Expected = NetherCS\SniffGenericTemplate::ConvertMethodToPascalCase($Current);

		if($Current !== $Expected) {
			$this->TransactionBegin();
			$this->Fix(sprintf(static::FixReason,$Current),$Expected,$StackPtr);
			$this->UpdateFoundUses($Current,$Expected);
			$this->TransactionCommit();
		}

		return;
	}

	protected function
	UpdateFoundUses(String $Current, String $Expected):
	Void {

		$StackPtr = 0;
		$OpenPtr = NULL;
		$ClosePtr = NULL;
		$Seek = NULL;
		$Before = NULL;
		$After = NULL;
		$Reference = NULL;
		$Property = NULL;

		$Scope = array_reverse(array_keys($this->Stack[$this->StackPtr]['conditions']));

		if(!count($Scope))
		return;

		$OpenPtr = $this->Stack[$Scope[0]]['scope_opener'];
		$ClosePtr = $this->Stack[$Scope[0]]['scope_closer'];
		$StackPtr = $OpenPtr;

		while($StackPtr < $ClosePtr) {
			$Seek = $this->GetTypeFromStack($StackPtr);
			$Before = NULL;

			if($Seek === T_PAAMAYIM_NEKUDOTAYIM) {
				$Before = $this->File->FindPrevious([T_STATIC,T_SELF,T_VARIABLE],($StackPtr-1),NULL);
				$After = $this->File->FindNext([T_STRING],($StackPtr+1),NULL);
			}

			elseif($Seek === T_OBJECT_OPERATOR) {
				$Before = $this->File->FindPrevious([T_VARIABLE],($StackPtr-1),NULL);
				$After = $this->File->FindNext([T_STRING],($StackPtr+1),NULL);
			}

			if($Before) {
				$Property = $this->GetContentFromStack($After);

				if($Property === $Current)
				$this->Fix(
					sprintf(static::FixUpdate,$Current),
					$Expected,
					$After
				);
			}

			$StackPtr++;
		}

		return;
	}

}
