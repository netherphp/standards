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

## Inline Documentation.

All symbol documentation is done with Nether Senpai format documentation.
Unlike typical docblocks, these come after the symbol they define on the
same indention level. Senpai blocks are opened with /\*// and closed with
//\*/ - more on the documentation when I finish writing this document
and start writing that one.

Nether Senpai generates as much documentation from the code itself before
reading the comment that describes it. This reduces the amount of junk
you need to manually write in the documentation. Once PHP 7 lands and you
can use primative typehints and return declarations, you will be able
to write even less documentation.

NN will use Senpai notation until the day PHP has real annotation support
that is not via the slow Reflection system you do not want to use within
a production project.

## Logical Control Blocks

Statements like IF or WHERE may be written with or without their braces
that denote the block of code. It is your choice. Nether code prefers
omitting the braces when the control block is super simple. When omitting the
braces it is preferred that the code remains on the same indention level
as the control structure. When braces are included, the opening brace will
come after the control structure, and the code within indented to the next
level.

**A WHILE with omitted braces.**

```php
<?php

$list = [];

while($Row = $Query->Next())
$list[] = $Row;

```

**A WHILE with braces.**
```php
<?php

$list = [];

while($Row = $Query->Next()) {
	$Row->Cached = false;
	$list[] = $Row;
}
```

## Argument Lists and Array Definitions

If a call to a function or method requires multiple arguments, and that list
may get lengthy or hard to read, arguments will be defined on new lines on the
next level of indention.

```php
<?php

preg_match(
	'/^https?:\/\/([^\/]+)/',
	$InputURL,
	$Matches
);
```

The same rules apply when defining an array with data, with the added feature
that it is preferable to align the deliminters via extra space characters to
pad the alignment. The alignment should match the longest element in that
definition.

```php
<?php

$Dataset = [
	'Something' => 1,
	'Else'      => 2,
	'MoreData'  => 3
];
```

If items in the array are being grouped during their definition it is
acceptable to have multiple levels of padding unique to each group.

```php
<?php

$Dataset = [
	// these are the main system options.
	'Something' => 1,
	'Else'      => 2,
	'MoreData'  => 3,

	// mostly optional for whatever.
	'Four' => 4,
	'Five' => 5,
	'Six'  => 6
];
```

## Files and Class Autoloading

Each class will be in its own file. The fully qualified name of the class
including the namespace will match the file path on disk. This will allow
you to use your choice of either PSR-0 or PSR-4 style autoloading. File
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

## Method Definitions.

Methods will be defined in PascalCase. The method name itself will be defined
nether the access keywords. This is to prevent your eyes from having to jump
left and right constantly while scrolling through the file. If you have
concerns with finding methods by doing something like doing a search for
"ion SetSomething" then you should instead famliarise yourself with the symbol
finding feature of your editor. Opening braces will be after the method
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

You would not follow this naming convention if the split methods are going
to be reusable by many different processes. This section is purely for
separation of concerns where the separated actions are useless on their
own.

To split a long method into smaller units of code, of which may not be
usuable on their own, it is your desgression at how to structure your methods
for your preferred method of unit testing. These reduced concern methods shall
be prefixed with the method name they are designed to work with, with the
descriptive action being separated by an underscore in the method name.

```php
<?php

class Project {

	public function
	GetFileContents($Filename) {
	/*//
	@argv string Filename
	@return object or false
	given a filename return the object built from the contents of that file.
	//*/

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
	/*//
	@return string
	check that the file is readable from the filesystem.
	//*/

		if(!file_exists($Filename) || !is_readable($Filename)
		throw new Exception("{$Filename} not found or unreadable.");

		return file_get_contents($Filename);
	}

	protected function
	GetFileContents_ParseData($Data) {
	/*//
	@return object
	check that the file was parsable.
	//*/

		$Obj = json_decode($Data);

		if(!is_object($Data))
		throw new Exception("Unable to parse data.");

		return $Obj;
	}

}

```

