<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class FunctionBodyBracesSameLineSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method and Function open body brace must be on the same line.';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$EndPtr = NULL;
		$ReturnPtr = NULL;
		$WhiteSpace = NULL;
		$Seek = NULL;

		$EndPtr = $this->File->FindNext([T_OPEN_CURLY_BRACKET,T_SEMICOLON],($StackPtr+1),NULL);

		if(!$EndPtr)
		return;

		// we found an abstract function.

		if($this->GetTypeFromStack($EndPtr) === T_SEMICOLON)
		return;

		// find when this function ends its declaration.

		$ReturnPtr = $this->File->FindPrevious([T_STRING,T_CLOSE_PARENTHESIS],($EndPtr-1),NULL);

		if(!$ReturnPtr)
		return;

		// are we on the same line?

		if($this->GetLineFromStack($ReturnPtr) !== $this->GetLineFromStack($EndPtr)) {

			$StackPtr = $ReturnPtr;
			while($StackPtr < $EndPtr && ($Seek = $this->GetTypeFromStack($StackPtr))) {
				if($Seek === T_WHITESPACE) {
					$Whitespace = $this->GetContentFromStack($StackPtr);

					// replace the newline with a single space.

					if(strpos($Whitespace,"\n") !== FALSE) {
						var_dump("fix announce");
						$this->SubmitFix(
							static::FixReason,
							$Whitespace,
							' ',
							$StackPtr
						);
					}

					// replace any other whitespaces in the way with nothingness.

					else {
						var_dump("fix silent");
						$this->SubmitFixSilent('',$StackPtr);
					}

				}

				$StackPtr++;
			}
		}

		return;
	}

}
