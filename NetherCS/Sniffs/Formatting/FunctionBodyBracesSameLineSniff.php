<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class FunctionBodyBracesSameLineSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method/Function open brace must be on the same line (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$EndPtr = NULL;
		$ReturnPtr = NULL;
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
			$this->FixBegin(sprintf(static::FixReason,$this->File->GetDeclarationName($this->StackPtr)));
			$this->TransactionBegin();

			$StackPtr = $ReturnPtr;
			while($StackPtr < $EndPtr && ($Seek = $this->GetTypeFromStack($StackPtr))) {
				if($Seek === T_WHITESPACE) {
					$Whitespace = $this->GetContentFromStack($StackPtr);

					// replace whitespace with newline with a single space. any other whitespace
					// will be made of up spaces or tabs shall be nullified.

					if(strpos($Whitespace,"\n") !== FALSE)
					$this->FixReplace(' ',$StackPtr);

					else
					$this->FixReplace('',$StackPtr);
				}

				$StackPtr++;
			}

			$this->TransactionCommit();
		}

		return;
	}

}
