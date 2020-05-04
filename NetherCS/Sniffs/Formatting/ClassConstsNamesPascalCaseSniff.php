<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class ClassConstsNamesPascalCaseSniff
extends NetherCS\Sniffers\ScopeClassConsts {

	const
	FixReason = 'NN: Class Consts must be PascalCase',
	FixUpdate = 'NN: Update internal use of altered Consts';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$Indent = $this->GetCurrentIndent();
		$Current = $this->GetContentFromStack($this->StackPtr);
		$Expected = static::ConvertToPascalCase($Current);

		if($Current !== $Expected) {
			$this->TransactionBegin();

			$this->SubmitFixAndShow(
				static::FixReason,
				$Current,
				$Expected,
				$this->StackPtr
			);

			$this->UpdateFoundUses($Current,$Expected);
			$this->TransactionCommit();
		}

		return;
	}

	protected function
	UpdateFoundUses(String $Current, String $Expected):
	Void {

		$StackPtr = 0;
		$OpenPtr = NULL;
		$ClosePtr = NULL;
		$Seek = NULL;
		$Before = NULL;
		$After = NULL;
		$Property = NULL;

		$Scope = array_reverse(array_keys($this->Stack[$this->StackPtr]['conditions']));

		if(!count($Scope))
		return;

		$OpenPtr = $this->Stack[$Scope[0]]['scope_opener'];
		$ClosePtr = $this->Stack[$Scope[0]]['scope_closer'];
		$StackPtr = $OpenPtr;

		while($StackPtr < $ClosePtr) {
			$Seek = $this->GetTypeFromStack($StackPtr);
			$Before = NULL;

			if($Seek === T_PAAMAYIM_NEKUDOTAYIM) {
				$Before = $this->File->FindPrevious([T_STATIC,T_SELF,T_VARIABLE],($StackPtr-1),NULL);
				$After = $this->File->FindNext([T_STRING],($StackPtr+1),NULL);
			}

			if($Before) {
				$Property = $this->GetContentFromStack($After);

				if($Property === $Current)
				$this->SubmitFixAndShow(
					static::FixUpdate,
					$Current,
					$Expected,
					$After
				);
			}

			$StackPtr++;
		}

		return;
	}

}
