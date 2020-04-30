<?php

namespace NetherCS;

use \PHP_CodeSniffer as PHPCS;

abstract class SniffTemplate
implements PHPCS\Sniffs\Sniff {

	protected
	$TokenTypes = [];

	protected
	$Stack = NULL;

	protected
	$StackPtr = NULL;

	public function
	Register():
	Array {

		return $this->TokenTypes;
	}

	public function
	Process(PHPCS\Files\File $File, $StackPtr):
	Void {

		$this->File = $File;
		$this->StackPtr = $StackPtr;
		$this->Stack = $File->GetTokens();

		$this->Execute();

		return;
	}

	protected function
	GetContentFromStack($Ptr=NULL):
	?String {

		$Ptr ??= $this->StackPtr;

		if(!array_key_exists($Ptr,$this->Stack))
		return NULL;

		return $this->Stack[$Ptr]['content'];
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
		$this->File->Fixer->ReplaceToken($Ptr,$New);

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	abstract public function
	Execute(): Void;

}


