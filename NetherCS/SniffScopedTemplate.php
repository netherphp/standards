<?php

namespace NetherCS;

use \PHP_CodeSniffer as PHPCS;

use \Exception as Exception;

abstract class SniffScopedTemplate
extends PHPCS\Sniffs\AbstractScopeSniff {

	protected mixed
	$File;

	use
	Traits\SniffUtility;

	protected
	$Stack       = NULL,
	$StackPtr    = NULL,
	$ScopePtr    = NULL,
	$TokenScopes = NULL,
	$TokenTypes  = NULL;

	public function
	__Construct() {

		if(!is_array($this->TokenScopes) || !is_array($this->TokenTypes))
		throw new Exception('TokenScopes or TokenTypes lists are not defined.');

		parent::__construct(
			$this->TokenScopes,
			$this->TokenTypes
		);

		return;
	}

	public function
	ProcessTokenWithinScope(PHPCS\Files\File $File, $StackPtr, $ScopePtr):
	Void {

		$this->File = $File;
		$this->StackPtr = $StackPtr;
		$this->ScopePtr = $ScopePtr;
		$this->Stack = $File->GetTokens();

		// check we are in the expected scope.

		if($this->GetCurrentScope() !== $this->ScopePtr)
		return;

		// allow filtering.

		if(!$this->Allow())
		return;

		// process element.

		$this->Execute();
		return;
	}

	protected function
	ProcessTokenOutsideScope(PHPCS\Files\File $File, $StackPtr):
	Void {

		$this->File = $File;
		$this->StackPtr = $StackPtr;
		$this->ScopePtr = NULL;
		$this->Stack = $File->GetTokens();

		if(!$this->AllowOther())
		return;

		$this->ExecuteOther();
		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	Execute():
	Void {

		return;
	}

	public function
	ExecuteOther():
	Void {

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected function
	Allow():
	Bool {

		return TRUE;
	}

	protected function
	AllowOther():
	Bool {

		return TRUE;
	}

}


