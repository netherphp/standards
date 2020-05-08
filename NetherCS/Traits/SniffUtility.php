<?php

namespace NetherCS\Traits;

trait SniffUtility {

	static public
	$DefaultTypes = [
		'Void', 'Int', 'Float', 'Double', 'String', 'Bool', 'Boolean',
		'Array', 'Callable', 'Object', 'Mixed'
	];

	static public function
	GetDefaultType($Input):
	Int {

		return array_search(
			strtolower($Input),
			array_map('strtolower',static::$DefaultTypes)
		);
	}

	static public function
	IsDefaultType($Input):
	Int {

		return static::GetDefaultTypeKey($Input) === FALSE;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected function
	GetEOL():
	String {

		return $this->File->eolChar;
	}

	protected function
	GetCurrentIndent($Ptr=NULL):
	?String {

		$Ptr = $Ptr ?? $this->StackPtr;
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

		$Ptr = $Ptr ?? $this->StackPtr;

		if(!$Ptr)
		return NULL;

		if(!array_key_exists($Ptr,$this->Stack))
		return NULL;

		return $this->Stack[$Ptr]['content'];
	}

	protected function
	GetTypeFromStack($Ptr=NULL) {

		// turns out not all the types are ints as phpcs
		// made a few up to fill in gaps. wtb mixed returns.

		$Ptr = $Ptr ?? $this->StackPtr;

		if(!array_key_exists($Ptr,$this->Stack))
		return NULL;

		return $this->Stack[$Ptr]['code'];
	}

	protected function
	GetLineFromStack($Ptr=NULL):
	?Int {

		$Ptr = $Ptr ?? $this->StackPtr;

		if(!array_key_exists($Ptr,$this->Stack))
		return NULL;

		return (Int)$this->Stack[$Ptr]['line'];
	}

	protected function
	GetTypeStringFromStack($Ptr=NULL):
	?String {

		$Ptr = $Ptr ?? $this->StackPtr;

		if(!array_key_exists($Ptr,$this->Stack))
		return NULL;

		return $this->Stack[$Ptr]['type'];
	}

	protected function
	GetFunctionNamePtr($Ptr=NULL):
	?Int {

		$Ptr = $Ptr ?? $this->StackPtr;
		$NamePtr = $this->File->findNext([T_STRING,T_OPEN_CURLY_BRACKET,T_SEMICOLON],$Ptr,NULL);

		if($this->GetTypeFromStack($NamePtr) !== T_STRING)
		return NULL;

		return $NamePtr;
	}

	protected function
	GetCurrentScope($Ptr=NULL):
	?Int {

		$Ptr = $Ptr ?? $this->StackPtr;
		$Result = NULL;

		if(!array_key_exists('conditions',$this->Stack[$Ptr]))
		return NULL;

		if(!count($this->Stack[$Ptr]['conditions']))
		return NULL;

		$Result = array_keys(array_reverse(
			$this->Stack[$Ptr]['conditions'],
			TRUE
		))[0];

		return $Result;
	}

	protected function
	GetDeclarationName($Ptr=NULL):
	String {
	/*//
	use phpcs's declaration finder but return a default if not found due
	to using this on various anonymous structures.
	//*/

		return (
			$this->File->GetDeclarationName($this->StackPtr)
			?? "λ:{$this->GetLineFromStack($this->StackPtr)}"
		);
	}

	protected function
	BumpMetric(String $MetricName,String $MetricValue, Int $Ptr=NULL):
	Void {

		$Ptr = $Ptr ?? $this->StackPtr;

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

		$Ptr = $Ptr ?? $this->StackPtr;
		$Fix = NULL;

		$Fix = $this->File->AddFixableError(
			$Reason,
			$Ptr,
			'Found',
			[$Old,$New]
		);

		if($Fix === TRUE) {
			($this->File->fixer)
			->ReplaceToken($Ptr,$New);
		}

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

		$Ptr = $Ptr ?? $this->StackPtr;
		$Fix = $this->File->fixer->enabled;

		if($Fix === TRUE) {
			($this->File->fixer)
			->ReplaceToken($Ptr,$New);
		}

		return;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	protected function
	Fix(String $Reason, String $Content, Int $ReportPtr=NULL, Int $FixPtr=NULL):
	Void {
	/*//
	push a single simple fix into the source without having to manually do a
	call to FixBegin and FixReplace. if one stack ptr is given then it will
	report and replace at that ptr. if two stack ptrs are given then it will
	report the problem as being at the first one, but submit the fix at the
	second.
	//*/

		$ReportPtr = $ReportPtr ?? $this->StackPtr;
		$FixPtr = $FixPtr ?? $ReportPtr;

		if($this->FixBegin($Reason,$ReportPtr))
		$this->FixReplace($Content,$FixPtr);

		return;
	}

	protected function
	FixBegin(String $Reason, Int $Ptr=NULL):
	Bool {
	/*//
	this will open an error report without doing any fixes itself. in this
	case the stack pointer is only used to report the original line that
	the error occured on to make the report more readable.
	//*/

		$Ptr = $Ptr ?? $this->StackPtr;

		$Result = $this->File->addFixableError(
			$Reason,
			$Ptr,
			static::class
		);

		return $Result;
	}

	protected function
	FixInsertBefore(String $Content, Int $Ptr=NULL):
	Void {
	/*//
	insert a fix in front of the specified token. you should only be using
	this after a successful call to FixBegin.
	//*/

		$Ptr = $Ptr ?? $this->StackPtr;

		($this->File->fixer)
		->addContentBefore($Ptr,$Content);

		return;
	}

	protected function
	FixReplace(String $Content, Int $Ptr=NULL):
	Void {
	/*//
	replace a token with specified content. you should only be using
	this after a successful call to FixBegin.
	//*/

		$Ptr = $Ptr ?? $this->StackPtr;

		($this->File->fixer)
		->replaceToken($Ptr,$Content);

		return;
	}

	protected function
	TransactionBegin():
	Void {
	/*//
	tell the fixer we wish to perform a transaction based fix so that
	everything happens together or fails together.
	//*/

		($this->File->fixer)
		->beginChangeset();

		return;
	}

	protected function
	TransactionCommit():
	Void {
	/*//
	tell the fixer we wish to commit a transaction based fix so that
	everything happens together or fails together.
	//*/

		($this->File->fixer)
		->endChangeset();

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
