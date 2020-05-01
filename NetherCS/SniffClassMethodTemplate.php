<?php

namespace NetherCS;

use \PHP_CodeSniffer as PHPCS;

abstract class SniffClassMethodTemplate
extends PHPCS\Sniffs\AbstractScopeSniff {

	protected
	$Stack    = NULL,
	$StackPtr = NULL,
	$ScopePtr = NULL;

	public function
	__construct() {
		parent::__construct(
			[ T_CLASS, T_ANON_CLASS, T_INTERFACE, T_TRAIT ],
			[ T_FUNCTION ]
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

	protected function
	GetContentFromStack($Ptr=NULL):
	?String {

		$Ptr ??= $this->StackPtr;

		if(!array_key_exists($Ptr,$this->Stack))
		return NULL;

		return $this->Stack[$Ptr]['content'];
	}

	protected function
	GetTypeFromStack($Ptr=NULL):
	?Int {

		$Ptr ??= $this->StackPtr;

		if(!array_key_exists($Ptr,$this->Stack))
		return NULL;

		return (Int)$this->Stack[$Ptr]['code'];
	}

	protected function
	GetTypeStringFromStack($Ptr=NULL):
	?String {

		$Ptr ??= $this->StackPtr;

		if(!array_key_exists($Ptr,$this->Stack))
		return NULL;

		return $this->Stack[$Ptr]['type'];
	}

	protected function
	BumpMetric(String $MetricName,String $MetricValue, Int $Ptr=NULL):
	Void {

		$Ptr ??= $this->StackPtr;

		($this->File)
		->RecordMetric(
			$Ptr,
			$MetricName,
			$MetricValue
		);

		return;
	}

	protected function
	SubmitFix(String $Reason, String $Old, String $New, Int $Ptr=NULL):
	Void {

		$Ptr ??= $this->StackPtr;
		$Fix = NULL;

		$Fix = $this->File->AddFixableError(
			$Reason,
			$Ptr,
			'Found',
			[$Old,$New]
		);

		if($Fix === TRUE)
		($this->File->fixer)
		->ReplaceToken($Ptr,$New);

		return;
	}
	
	protected function
	SubmitFixAndShow(String $Reason, String $Old, String $New, Int $Ptr=NULL):
	Void {

		$this->SubmitFix(
			sprintf('%s (%s)',$Reason,$Old),
			$Old, $New, $Ptr
		);

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	abstract public function
	Execute(): Void;

}


