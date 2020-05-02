<?php

namespace NetherCS;

use \PHP_CodeSniffer as PHPCS;

abstract class SniffGenericTemplate
implements PHPCS\Sniffs\Sniff {

	use
	Traits\SniffUtility;

	protected
	$TokenTypes = [];

	protected
	$Stack = NULL;

	protected
	$StackPtr = NULL;

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Register():
	Array {
	/*//
	@override
	//*/

		return $this->TokenTypes;
	}

	public function
	Process(PHPCS\Files\File $File, $StackPtr):
	Void {
	/*//
	@override
	//*/

		$this->File = $File;
		$this->StackPtr = $StackPtr;
		$this->Stack = $File->GetTokens();

		$this->Execute();

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	abstract public function
	Execute(): Void;

}


