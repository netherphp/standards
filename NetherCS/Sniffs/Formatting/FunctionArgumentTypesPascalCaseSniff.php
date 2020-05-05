<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class FunctionArgumentTypesPascalCaseSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method/Function arg Types must be PascalCase';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$VarPtr = NULL;
		$Seek = NULL;
		$Current = NULL;
		$Expected = NULL;
		$IsDefaultType = NULL;

		while(($Seek = $this->GetTypeFromStack($StackPtr)) && $Seek !== T_OPEN_PARENTHESIS)
		$StackPtr++;

		while(($Seek = $this->GetTypeFromStack($StackPtr)) && $Seek !== T_CLOSE_PARENTHESIS) {
			if($Seek === T_VARIABLE) {
				$VarPtr = $this->File->FindPrevious([T_STRING,T_OPEN_PARENTHESIS,T_COMMA],($StackPtr-1),NULL);

				if($this->GetTypeFromStack($VarPtr) === T_STRING) {
					$Current = $this->GetContentFromStack($VarPtr);
					$Expected = static::ConvertToPascalCase($Current);
					$IsDefaultType = static::GetDefaultType($Current);

					if($IsDefaultType && $Current !== $Expected)
					$this->SubmitFixAndShow(
						static::FixReason,
						$Current,
						$Expected,
						$VarPtr
					);
				}
			}

			$StackPtr++;
		}

		return;
	}

}
