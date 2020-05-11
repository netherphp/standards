<?php

namespace someProjectVendor;

use \Nether;
use \Exception;

use Imagick;

class SomeProjectClass {

	public function 	SomeLambdaWambas(): 	Void {

		$List = [];

		usort($List,function($A,$B){
			// this method should have its arguments spaced out.
			return;
		});

		$Callable = function(){
			// this function should get a return added to it.
			return;
		};

		$Callable = function(Array $Input){
			$Arg = NULL;

			foreach($Input as $Arg)
			echo $Arg;

			// this method should get $Arg declared prior to the foreach loop.

			return;
		};

		$Callable = function() {
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
