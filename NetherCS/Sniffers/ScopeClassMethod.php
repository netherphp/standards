<?php

namespace NetherCS\Sniffers;

use \PHP_CodeSniffer as PHPCS;
use \NetherCS as NetherCS;

abstract class ScopeClassMethod
extends NetherCS\SniffScopedTemplate {

	protected
	$TokensScope = [ T_CLASS, T_ANON_CLASS, T_INTERFACE, T_TRAIT ],
	$TokensFind  = [ T_FUNCTION ];

}


