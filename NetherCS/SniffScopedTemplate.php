<?php

namespace NetherCS;

use \PHP_CodeSniffer as PHPCS;

use \Exception as Exception;

abstract class SniffScopedTemplate
extends PHPCS\Sniffs\AbstractScopeSniff {

	use
	Traits\SniffUtility;

	protected
	$Stack       = NULL,
	$StackPtr    = NULL,
	$ScopePtr    = NULL,
	$TokenScopes = NULL,
	$TokenTypes  = NULL;

	public function
	__construct() {

		if(!is_array($this->TokenScopes) || !is_array($this->TokenTypes))
		throw new Exception('TokenScopes or TokenTypes lists are not defined.');

		parent::__construct(
			$this->TokenScopes,
			$this->TokenTypes
		);

		return;
	}

	public function
	processTokenWithinScope(PHPCS\Files\File $File, $StackPtr, $ScopePtr):
	Void {

		$Cond = NULL;
		$DeepEnd = NULL;

		$this->File = $File;
		$this->StackPtr = $StackPtr;
		$this->ScopePtr = $ScopePtr;
		$this->Stack = $File->GetTokens();

		// check that we're really where we expect to be for dicking around
		// with a class method.

		$Cond = array_reverse(
			array_keys($this->Stack[$this->StackPtr]['conditions']),
			FALSE
		);

		if(!count($Cond))
		return;

		if($Cond[0] !== $this->ScopePtr)
		return;

		if(!$this->Allow())
		return;

		$this->Execute();
		return;
	}

	protected function
	processTokenOutsideScope(PHPCS\Files\File $File, $StackPtr):
	Void {

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	abstract public function
	Execute(): Void;

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected function
	Allow():
	Bool {

		return TRUE;
	}

}


