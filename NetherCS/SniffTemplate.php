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

		return $this->Stack[$Ptr]['code'];
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

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	static public function
	ConvertToPascalCase(String $Input):
	String {
	/*//
	convert strings into versions that look like pascal case if they
	are broken with underscores or spaces.
	//*/

		$Output = str_replace(' ','',ucwords(
			str_replace( '_',' ',$Input)
		));

		return $Output;
	}

	static public function
	ConvertMethodToPascalCase(String $Input):
	String {
	/*//
	convert strings that are methods to strings that look like pascal
	case. methods are allowed to have underscores in their reduction
	of concerns.
	//*/

		$Parts = explode('_',$Input);
		$Key = NULL;
		$Value = NULL;

		foreach($Parts as $Key => $Value)
		$Parts[$Key] = static::ConvertToPascalCase($Value);

		return join('_',$Parts);
	}

}


