# Nether [ˈne-thər]

> *Located toward the bottom or more distant part of something.*
> -- Merriam-Webster Dictionary

# Nether Standards Project

Known as Nether Notation, NN, N2, or N<sup>2</sup>

This is a project to document the code conventions used by the Nether Project.
As of the writing of this project there is a 99% chance you have never seen
code formatted like this before - and that is O.K. - not everything needs to
be cut with the same rules we used in 1976. These rules will typically depend
on the flexiblity of PHP, but some of the general formatting may be portable
to other languges depending on their parsers.

## General Standards

* PascalCaseAllTheThings
* Tabs for indenting.
* \n for new lines.
* explicit "return" at end of functions.
* design for strict types in mind.
* attempt to keep lines shorter than 80 characters.
* prefer single quotes when not using string evaluation.

## Files and Class Autoloading

Each class will be in its own file. The fully qualified name of the class
including the namespace will match the file path on disk. This will allow
you to use your choice of PSR-0 either PSR-4 style autoloading. File
names are case sensitive and should match the namespace and class
definition exactly.

* File: Nether/OneScript/Project.php
* Defines: Nether\OneScript\Project

## Namespace and Class definitions.

Everything shall be namespaced. Namespace use declarations should be grouped
with the namespace definition, while Class use declarations should be grouped
separately a line away. Followed by the class definition provided by the file.
Classes will be defined in PascalCase. Extension and Implementations will be
described nether. Opening braces will be on the last line of the definition.

```php
<?php

namespace Nether\OneScript;
use \ThirdParty1;
use \ThirdParty2;

use \ThirdParty3\Somespace\SomeClass;
use \ThirdParty3\Filterspace\SomeInterface;

class Project
extends SomeClass
implements SomeInterface {
	// ...
}
```

## Method definitions.

Methods will be defined in PascalCase. The method name itself will be defined
nether the access keywords. This is to prevent your eyes from having to jump
left and right constantly while scrolling through the file. If you have
concerns with finding methods by doing something like doing a search for
"ion SetSomething" then you should instead famliarise yourself with the symbol
finding feature of your editor. Opening breaces will be after the method
identifier.

```php
<?php

class Project {

	public function
	SetSomething() {
		// ...
		return;
	}

	static public function
	GetFromFile($Filename) {
		// ...
		return;
	}

}
```

Methods and functions will not "just end"  - they will include explicit
return when they are done. Arguments will be defined in PascalCase. When
PHP 7 lands, arguments will be defined with their strict type identifier.

## Method Reduction of Concerns

To split a long method into smaller units of code, of which may not be
usuable on their own, will be defined with an access level tighter than
that of the actual callable method. It is your desgression at how to
structure your methods for your preferred method of unit testing.

```php
<?php

class Project {

	public function
	GetFileContents($Filename) {

		try {
			$Data = $this->GetFileContents_ReadFile($Filename);
			$Obj = $this->GetFileContents_ParseData($Data);
		}

		catch(Exception $e) {
			// log error or something.
			return false;
		}

		return $Obj;
	}

	protected function
	GetFileContents_ReadFile($Filename) {

		if(!file_exists($Filename) || !is_readable($Filename)
		throw new Exception("{$Filename} not found or unreadable.");

		return file_get_contents($Filename);
	}

	protected function
	GetFileContents_ParseData($Data) {

		$Obj = json_decode($Data);

		if(!is_object($Data))
		throw new Exception("Unable to parse data.");

		return $Obj;
	}

}

```

