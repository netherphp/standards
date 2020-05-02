<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ConstantsBoolNullAllCapsSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_TRUE, T_FALSE, T_NULL ];

	const
	FixReason = 'NN: TRUE, FALSE, and NULL must be uppercase',
	MetricName = 'BOOL and NULL Constant Case',
	ResultMixed = 'Mixed',
	ResultLower = 'Lower',
	ResultProper = 'Proper';

	public function
	Execute():
	Void {

		$Current = $this->GetContentFromStack();
		$Expected = strtoupper($Current);

		if($Current !== $Expected) {
			if(preg_match('/[A-Z]/',$Current))
			$this->BumpMetric(static::MetricName,static::ResultMixed);
			else
			$this->BumpMetric(static::MetricName,static::ResultLower);

			$this->SubmitFixAndShow(static::FixReason,$Current,$Expected);
			return;
		}

		$this->BumpMetric(static::MetricName,static::ResultProper);
		return;
	}

}
