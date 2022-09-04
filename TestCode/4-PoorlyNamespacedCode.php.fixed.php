<?php

namespace SomeProjectVendor\SomeProject;

use \NetherCS;
use \Imagick;

use
\Throwable,
\Exception;

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
	void {

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
	void {

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

		$this->GetImagick($Array);

		$this->GetImagick(
			$Array
		);

		str_replace(
			'one',
			'two',
			'three'
		);

		return;
	}

	public function
	NullableTypeTho(?string $What):
	?int {

		return 1;
	}

}

trait Trait1 { }
trait Trait2 { }
trait Trait3 { }
trait Trait4 { }
trait Trait5 { }
