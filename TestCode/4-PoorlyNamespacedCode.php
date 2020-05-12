<?php

namespace someProjectVendor\someProject;

use \NetherCS;
use \Imagick;

use \Throwable, \Exception;

class SomeProjectClass {

	use Trait1;

	use Trait2,Trait3,  Trait4;

	use Trait5 { }

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

trait Trait1 { }
trait Trait2 { }
trait Trait3 { }
trait Trait4 { }
trait Trait5 { }
