<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;

class ClassPropertiesPascalCaseSniff
extends NetherCS\Sniffers\ScopeClassProperties {

	const
	FixReason = 'NN: Class Properties must be PascalCased (%s)',
	FixUpdate = 'NN: Update internal use of altered Class Properties (%s)';

	public function
	Execute():
	Void {

		$Current = $this->GetContentFromStack($this->StackPtr);
		$Expected = static::ConvertVariableToPascalCase($Current);

		if($Current !== $Expected) {
			$this->TransactionBegin();
			$this->Fix(
				sprintf(static::FixReason,$Current),
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
		$Reference = NULL;
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
				$After = $this->File->FindNext([T_VARIABLE],($StackPtr+1),NULL);
			}

			elseif($Seek === T_OBJECT_OPERATOR) {
				$Before = $this->File->FindPrevious([T_VARIABLE],($StackPtr-1),NULL);
				$After = $this->File->FindNext([T_STRING],($StackPtr+1),NULL);
			}

			if($Before) {
				$Property = $this->GetContentFromStack($After);

				switch($Seek) {
					case T_PAAMAYIM_NEKUDOTAYIM:
						if($Property === $Current)
						$this->Fix(
							sprintf(static::FixUpdate,$Current),
							$Expected,
							$After
						);
					case T_OBJECT_OPERATOR:
						if($Property === substr($Current,1))
						$this->Fix(
							sprintf(static::FixUpdate,substr($Current,1)),
							substr($Expected,1),
							$After
						);
					break;
				}
			}

			$StackPtr++;
		}

		return;
	}

}
