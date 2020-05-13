# Nether [ˈne-thər]

[![PHPCS Test](https://github.com/netherphp/standards/workflows/PHPCS%20Test/badge.svg)](https://github.com/netherphp/standards/actions?query=workflow%3A%22PHPCS+Test%22)

> *Located toward the bottom or more distant part of something.*
> -- Merriam-Webster Dictionary


# Nether Standards Project

Known as Nether Notation, NN, N2, or N<sup>2</sup>

This is a project to document the code conventions used by the Nether Project. As of the writing of this project there is a 99% chance you have never seen code formatted like this before - and that is O.K. - not everything needs to be cut with the same rules we used in 1972. These rules will typically depend on the flexiblity of PHP, but some of the general formatting may be portable to other languges depending on their parsers.

A lot of the rules here are based on the concept of being explicit. The developer will always explictly state their intentions, never allowing for default behaviours. This is for a few reasons. First being it shows that the developer has actually thought about what their code is doing. Second, it helps lessen backwards compatbility issues in the future when a default behaviour changes. And yes, it does happen. Finally, the entire format and rules are based on the concept that the code can be as self documenting as possible to minimise the amount of metadata needed to describe various entities.


## Automated Formatting

This repository contains a `phpcs` standard for testing and automated reformatting of source to fit the standard. For instructions on installation and automated testing against this standard please refer to the Wiki page:

https://github.com/netherphp/standards/wiki/Nether-Notation-Coding-Standard-for-PHP_CodeSniffer


## General Standards

* PascalCaseAllTheThings except:
* UPPERCASE for boolean constants (TRUE, FALSE) and NULL.
* Tabs for indenting.
* \n for new lines.
* initialize variables in scopes prior to use.
* explicit "return" at end of functions.
* design for strict types in mind.
* attempt to keep lines shorter than 80 characters.
* prefer single quotes when not using string evaluation.
* anything that can have a type, should have a type.
* no derp commas (trailing commas at the end of arrays, etc).

## Inline Documentation.

All symbol documentation is done with Nether Senpai format documentation. Unlike typical docblocks, these come after the symbol they define on the same indention level. Senpai blocks are opened with /\*// and closed with //\*/ - more on the documentation when I finish writing this document and start writing that one.

Nether Senpai generates as much documentation from the code itself before noticing the comment that describes it. This reduces the amount of junk you need to manually write in the documentation. Taking advantage of featured added in PHP 7.0+ 7.1 most code can be completely self document itself.

NN will use Senpai notation until the day PHP has real annotation support that is not via the slow Reflection system you do not want to use within a production project.

This means a typical method will look like this:

```php
<?php

class Project {

	public function
	DoSomething(Int $Count):
	Void {
	/*//
	this method does something. we do not know exactly because this example
	is not important enough to fill with an implementation.
	//*/

		$Cur = 0;
		while($Cur < $Count) {
			// ...
		}

		return;
	}

}
```

And when code folded in an editor like Sublime Text....

```php
<?php

class Project {

	public function
	DoSomething(Int $Count):
	Void {
	/*//
	this method does something. we do not know exactly because this example
	is not important enough to fill with an implementation.
	//*/ [...]
	}

}
```

## Logical Control Blocks

Statements like IF or WHERE may be written with or without their braces that denote the block of code. It is your choice. Nether code prefers omitting the braces when the control block is super simple. When omitting the braces it is preferred that the code remains on the same indention level as the control structure. When braces are included, the opening brace will come after the control structure, and the code within indented to the next level. Structures with omitted braces will be isolated by empty lines.

**A WHILE with omitted braces.**

```php
<?php

$List = [];

while($Row = $Query->Next())
$List[] = $Row;

```

**A WHILE with braces.**
```php
<?php

$List = [];
while($Row = $Query->Next()) {
	$Row->Cached = FALSE;
	$List[] = $Row;
}
```

## Argument Lists and Array Definitions

If a call to a function or method requires multiple arguments, and that list may get lengthy or hard to read, arguments will be defined on new lines on the next level of indention.

```php
<?php

preg_match(
	'/^https?:\/\/([^\/]+)/',
	$InputURL,
	$Matches
);
```

The same rules apply when defining an array with data, with the added feature that it is preferable to align the delimiters via extra space characters to pad the alignment. The alignment should match the longest element in that definition.

```php
<?php

$Dataset = [
	'Something' => 1,
	'Else'      => 2,
	'MoreData'  => 3
];
```

If items in the array are being grouped during their definition it is acceptable to have multiple levels of padding unique to each group.

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

## Strings

If a string DOES NOT require data evaluation then single quotes will be used. If a string DOES require data evaluation then double quotes may be used, if the resulting string will not cause the line length to get unwieldy. The preferred method for building or concatinating long strings is via the `sprintf` function.

```php
<?php

$Straight = 'some straight string without eval';
$Evaluated = "not {$Straight}";

$PageURL = sprintf(
	'%s://%s/%s',
	$RequestProtocol,
	$RequestDomain,
	$RequestPath
);
```

Even in simple cases, the overhead of `sprintf` is is preferred for readability when indecisive. Both evaluation and `sprintf` is acceptable so it is left to the author to determine the best choice based on what the code needs to do.

```php
<?php

$String = "User: {$Name}";
$String = sprintf('User: %s',$Name);
```

However, anytime it is needed to do something like call a method to build a string then the `sprintf` is the only acceptable choice.

```php
<?php

$String = sprintf(
	'User: %s',
	$User->GetName()
);
```

Literal concationation with the dot operator is not considered acceptable in any situation.

```php
<?php

$String = 'User: ' . $Name; // no.
```

## Files and Class Autoloading

Each class will be in its own file. The fully qualified name of the class including the namespace will match the file path on disk. This allows use of either PSR-0 or PSR-4 style autoloading. File names are case sensitive and should match the namespace and class definition exactly.

* File: Nether/OneScript/Project.php
* Defines: Nether\OneScript\Project

## Namespace and Class definitions.

Everything shall be namespaced. Namespace use declarations should be grouped with the namespace definition, while Class use declarations should be grouped separately a line away. Followed by the class definition provided by the file. Classes will be defined in PascalCase. Extension and Implementations will be described nether. Opening braces will be on the last line of the definition.

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

It is to be avoided using preceeding backslashes in main executing code to demote pulling from the root namespace PHP. Instead it is preferred to set up `use` statements accordingly for the access required.

```php
<?php

namespace MyApp\Subspace;
use \Nether;
```

To provide access to the Nether namespace without having to include the `\` each time access a class in the Nether namespace is needed.

## Method Definitions.

Methods will be defined in PascalCase. The method name itself will be defined nether to the access keywords. Opening braces will be after the method identifier. Methods will always have an access keyword. If the access keyword was going to be omitted, then it will be unomitted with the keyword `public`.

Methods shall be declared as explicit as possible. Their arguments should have their accepted types declared as well as the return type of the method. The return type shall be placed nether the method with the opening brace after.

Methods which return nothing, as in not an explicit NULL, will have their type declared as Void. Void methods and functions will not "just end", they will include explicit return when they are done. Nullable Types are encouraged where they could make an API more simple to check result against.

```php
<?php

class Project {

	public function
	SetSomething():
	Void {
		// ...
		return;
	}

	static public function
	GetFromFile(String $Filename):
	String {
		// ...
		return $Contents;
	}

}
```

## Methods with Variable or Optional Arguments

If a method will take a lot of arguments, or has a handful of optional ones, it is preferable that instead of a long argument list that that method accept an object or array instead. Something like `Nether\Object` can be used to help ensure population for optional arguments with enforced defaults. This eliminates the constant nagging feeling of having forgot what order the arguments should be presented in as long as you can recall what it needs.

```php
<?php

class Project {

	public function
	Search($Input=NULL):
	SearchResult {
	/*//
	@argv object Input
	@argv array Input
	//*/

		$Input = new Nether\Object($Input,[
			'Query' => NULL,
			'Page'  => 1,
			'Limit' => 25,
			'Owner' => NULL
		]);

		// ...

		return $Result;
	}

}
```

## Method Reduction of Concerns

The this naming convention would not be used if the split methods are to be reusable by many different processes. This section is mainly for separation of concerns where the separated actions are useless on their own.

To split a long method into smaller units of code, reduced concern methods shall be prefixed with the method name they are designed to work with, with the descriptive action being separated by a netherscore in the method name.

```php
<?php

class Project {

	public function
	GetFileContents(String $Filename) {
	/*//
	@return ?StdClass
	given a filename return the object built from the contents of that file.
	//*/

		$Data = NULL;
		$Obj = NULL;
		$Error = NULL;

		try {
			$Data = $this->GetFileContents_ReadFile($Filename);
			$Obj = $this->GetFileContents_ParseData($Data);
		}

		catch(Exception $Error) {
			// log error or something.
			return NULL;
		}

		return $Obj;
	}

	protected function
	GetFileContents_ReadFile(String $Filename):
	String {
	/*//
	check that the file is readable from the filesystem.
	//*/

		if(!file_exists($Filename) || !is_readable($Filename)
		throw new Exception("{$Filename} not found or unreadable.");

		return file_get_contents($Filename);
	}

	protected function
	GetFileContents_ParseData(String $Data):
	StdClass {
	/*//
	check that the file was parsable.
	//*/

		$Obj = json_decode($Data);

		if(!is_object($Data))
		throw new Exception("Unable to parse data.");

		return $Obj;
	}

}
```

## Method Chaining

When using chained methods and the line may potentially become unwieldy, then each link in the chain will be placed on a new line at the same indention level as the symbol the chain originates from. Calls like this will be isolated by empty lines above and below them.

```php
<?php

$DB = new Nether\Database;
$Query = NULL;

($Query = $DB->NewVerse())
->Select('Table')
->Fields(['One','Two','Three'])
->Where(['Five=:InputFive','Six=:InputSix'])
->Limit(25);

$Result = $DB->Query($Query,$Input);
```

It is preferred that you convey context awareness when chaining. In the above example all chained methods return the original object. Chaining should stop when the object returned is not the same object. The parens that wrap the first line of that query chain convey understanding by the authour of the context of this chain. This is the object, the following chain follows.

## Variable Scope Initialization.

While not required by PHP variables will be declared prior to later use at the beginning of their parent scopes - meaning all variables are to be be declared at the top of functions and methods and not invented in the middle of logic. If their value cannot be detemrined at declaration time, then they should be initialized as NULL until then.

This includes, and even specifically is targeting, any variables that would normally be invented on the fly in `for` or `foreach` loops, `catch`, etc.

```php
<?php

class Project {

	public function
	DetermineThisValue():
	Int {

		$Output = 0;
		$Child = NULL;

		foreach($this->TotallyAnArrayProperty as $Child) {
			if($Child->TotallyAnBoolProperty === TRUE)
			$Output++;
		}

		return $Output;
	}

}
```

## HTML Templating

When working within the scope of an HTML template file, code structures will be written using their Alternative Syntax. Short tag echo will not be used.

```php
<?php if($Stuff): ?>
<h1><a href="<?php echo $URL ?>"><?php echo $Title ?></a></h1>
<?php endif; ?>
```

