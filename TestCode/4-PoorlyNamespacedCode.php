<?php

namespace someProjectVendor;

use \NetherCS;
use \Imagick;

use \Throwable, \Exception;

class SomeProjectClass {

	use NetherCS\Traits\SniffUtility;

	public function
	SomeLambdaWambas():
	Void {

		$List = [];

		usort($List,function($A,$B){
			// this method should have its arguments spaced out.
			return;
		});

		$Callable = function(){
			// this function should get a return added to it.
		};

		$Callable = function(Array $Input){
			foreach($Input as $Arg)
			echo $Arg;

			// this method should get $Arg declared prior to the foreach loop.

			return;
		};

		$Callable = function()
		{
			// this function should get its brace moved up.
			return;
		};

		return;
	}

	public function
	GetImagick():
	\Imagick {

		return new \Imagick;
	}

	public function
	AlsoGetImagick():
	Imagick {

		return $this->GetImagick();
	}

}
