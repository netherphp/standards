<?php

namespace NetherCS\Traits;

trait SniffUtility {

	protected function
	GetCurrentIndent($Ptr=NULL):
	?String {

		$Ptr ??= $this->StackPtr;
		$Start = $Ptr;
		$Type = '';
		$Whitespace = NULL;
		$Indent = '';


		while($Start) {
			$Start--;
			$Type = $this->GetTypeFromStack($Start);

			if($Type === T_WHITESPACE) {
				if(strpos($this->GetContentFromStack($Start),"\n") !== FALSE) {
					if($this->GetTypeFromStack($Start+1) === T_WHITESPACE) {
						$Indent = $this->GetContentFromStack($Start+1);
					}
					break;
				}
			}
		}

		return $Indent;
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
	GetTypeFromStack($Ptr=NULL) {
	/*//
	turns out not all the T_ consts are ints >_>
	//*/

		$Ptr ??= $this->StackPtr;

		if(!array_key_exists($Ptr,$this->Stack))
		return NULL;

		return $this->Stack[$Ptr]['code'];
	}

	protected function
	GetLineFromStack($Ptr=NULL):
	?Int {

		$Ptr ??= $this->StackPtr;

		if(!array_key_exists($Ptr,$this->Stack))
		return NULL;

		return (Int)$this->Stack[$Ptr]['line'];
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

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

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

	protected function
	SubmitFixSilent(String $New, Int $Ptr=NULL):
	Void {

		$Ptr ??= $this->StackPtr;
		$Fix = $this->File->fixer->enabled;

		if($Fix === TRUE)
		($this->File->fixer)
		->ReplaceToken($Ptr,$New);

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	static public function
	ConvertToPascalCase(String $Input):
	String {
	/*//
	convert strings into versions that look like pascal case if they
	are broken with underscores or spaces.
	//*/

		$Output = $Input;

		// a case for handling things that are all uppercase.
		// COMMONLY_LIKE_THIS

		if(!preg_match('/[a-z]/',$Output))
		$Output = ucwords(strtolower(str_replace('_',' ',$Output)));

		// handling everything else.
		// SomethingLikeThis_AndMaybeHavingThis
		// also_something_like_this

		else
		$Output = ucwords(str_replace('_',' ',$Output));

		////////

		$Output = str_replace(' ','',$Output);
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

	static public function
	ConvertVariableToPascalCase(String $Input):
	String {
	/*//
	convert strings that are variables to string sthat look like
	pascal case. take into consideration any special cases.
	//*/

		// don't mess with this.

		if($Input === '$this')
		return $Input;

		preg_match('/([$]+)([a-zA-Z0-9_]+)/',$Input,$Match);

		$Match[2] = static::ConvertToPascalCase($Match[2]);

		return "{$Match[1]}{$Match[2]}";
	}

}
