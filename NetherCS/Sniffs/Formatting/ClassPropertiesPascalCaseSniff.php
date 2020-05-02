<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class ClassPropertiesPascalCaseSniff
extends NetherCS\Sniffers\ScopeClassProperties {

	const
	FixReason = 'NN: Class Properties should be PascalCased.';

	public function
	Execute():
	Void {

		$Current = $this->GetContentFromStack($this->StackPtr);
		$Expected = static::ConvertVariableToPascalCase($Current);

		if($Current !== $Expected)
		$this->SubmitFixAndShow(
			static::FixReason,
			$Current,
			$Expected,
			$this->StackPointer
		);

		return;
	}

}
