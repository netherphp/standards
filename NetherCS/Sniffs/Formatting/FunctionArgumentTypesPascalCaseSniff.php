<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;

class FunctionArgumentTypesPascalCaseSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method/Function arg Types must be PascalCase (%s)';

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
					$IsDefaultType = static::GetDefaultType($Current);

					if($IsDefaultType)
					$Expected = strtolower($Current);
					else
					$Expected = static::ConvertToPascalCase($Current);

					if($IsDefaultType !== NULL && $Current !== $Expected)
					$this->Fix(
						sprintf(static::FixReason,$Current),
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
