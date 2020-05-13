<?php

namespace SomeProjectVendor\SomeProject;

use \NetherCS as NetherCS;
use \Imagick as Imagick;

use
\Throwable as Throwable,
\Exception as Exception;

class SomeProjectClass {

	use
	Trait1;

	use
	Trait2,
	Trait3,
	Trait4;

	use
	Trait5 { }

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

	public function
	DerpComma():
	Void {

		$Array = [1,2,3];

		$Array = [
			[ 1,2,3 ],
			[ 4,5,6 ]
		];

		$Array = [
			1,
			3,
			4
		];

		$Array = [ 1,2,3 ];

		$Array = [
			1,
			2,
			3
		];

		$Array = [
			[ 1, 2, 3 ],
			[ 4, 5, 6 ]
		];

		$this->GetImagick($Array,);

		$this->GetImagick(
			$Array,
		);

		return;
	}

}

trait Trait1 { }
trait Trait2 { }
trait Trait3 { }
trait Trait4 { }
trait Trait5 { }
