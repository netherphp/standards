<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;

class ClassBodyBracesSameLineSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_CLASS, T_INTERFACE, T_TRAIT ];

	const
	FixReason = 'NN: Class/Interface/Trait open brace must be on the same line (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$EndPtr = NULL;
		$ReturnPtr = NULL;
		$Seek = NULL;
		$ClassName = $this->GetDeclarationName($StackPtr);

		// find when the class definition ends.

		$EndPtr = $this->File->FindNext([T_OPEN_CURLY_BRACKET],($StackPtr+1),NULL);

		if(!$EndPtr)
		return;

		// find the next previous thing that is part of the definition.

		$ReturnPtr = $this->File->FindPrevious([T_WHITESPACE],($EndPtr-1),NULL,TRUE);

		if(!$ReturnPtr)
		return;

		// are we on the same line?

		if($this->GetLineFromStack($ReturnPtr) !== $this->GetLineFromStack($EndPtr)) {
			if($this->FixBegin(sprintf(static::FixReason,$ClassName))) {
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
		}

		return;
	}

}
