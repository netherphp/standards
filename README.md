# Nether [ˈne-thər]

> *Located toward the bottom or more distant part of something.*
> -- Merriam-Webster Dictionary

# Nether Standards Project

Known as Nether Notation, NN, N2, or N<sup>2</sup>

This is a project to document the code conventions used by the Nether Project.
As of the writing of this project there is a 99% chance you have never seen
code formatted like this before - and that is O.K. - not everything needs to
be cut with the same rules we used in 1972. These rules will typically depend
on the flexiblity of PHP, but some of the general formatting may be portable
to other languges depending on their parsers.

A lot of the rules here are based on the concept of being explicit. The
developer will always explictly state their intentions, never allowing for
default behaviours. This is for a few reasons. First being it shows that the
developer has actually thought about what their code is doing. Second, it
helps lessen backwards compatbility issues in the future when a default
behaviour changes. And yes, it does happen. Finally, the entire format and
rules are based on the concept that the code can be as self documenting as
possible to minimise the amount of metadata you need to write to describe
various entities.

## General Standards

* PascalCaseAllTheThings except:
* UPPERCASE for boolean constants (TRUE, FALSE) and NULL.
* Tabs for indenting.
* \n for new lines.
* explicit "return" at end of functions.
* design for strict types in mind.
* attempt to keep lines shorter than 80 characters.
* prefer single quotes when not using string evaluation.
* anything that can have a type, should have a type.

## Inline Documentation.

All symbol documentation is done with Nether Senpai format documentation.
Unlike typical docblocks, these come after the symbol they define on the
same indention level. Senpai blocks are opened with /\*// and closed with
//\*/ - more on the documentation when I finish writing this document
and start writing that one.

Nether Senpai generates as much documentation from the code itself before
noticing the comment that describes it. This reduces the amount of junk
you need to manually write in the documentation. Once PHP 7 lands and you
can use primative typehints and return declarations, you will be able
to write even less documentation.

NN will use Senpai notation until the day PHP has real annotation support
that is not via the slow Reflection system you do not want to use within
a production project.

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
	@argv int Count
	this method does something. we do not know exactly because this example
	is not important enough to fill with an implementation.
	//*/ [...]
	}

}
```

## Logical Control Blocks

Statements like IF or WHERE may be written with or without their braces
that denote the block of code. It is your choice. Nether code prefers
omitting the braces when the control block is super simple. When omitting the
braces it is preferred that the code remains on the same indention level
as the control structure. When braces are included, the opening brace will
come after the control structure, and the code within indented to the next
level. Structures with omitted braces will be isolated by empty lines.

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
that it is preferable to align the delimiters via extra space characters to
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

Arrays will be defined using their [] syntax unless you specifically require
to support PHP 5.3 or older.

## Strings

If a string does not require data evaluation then single quotes will be used.
If a string requires data evaluation then double quotes may be used, if
the resulting string will not cause the line length to get unwieldy. The
preferred method for building or concatinating long strings is via the
`sprintf` function.

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

Even in simple cases, the overhead of `sprintf` is is preferred for readability
when indecisive. Both evaluation and `sprintf` is acceptable so it is left to
the author to determine the best choice based on what the code needs to do.

```php
<?php

$String = "User: {$Name}";
$String = sprintf('User: %s',$Name);
```

However, anytime you need to do something like call a method to build a string
then the `sprintf` is the only acceptable choice.

```php
<?php

$String = sprintf(
	'User: %s',
	$User->GetName()
);
```

Literal concationation with the dot operator is only acceptable in extremely
narrow use cases that I cannot even think of right now.

```php
<?php

$String = 'User: ' . $Name; // no.
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

You will avoid using preceeding backslashes in your main executing code to
denote that you wish to pull from the root namespace PHP. Instead it is
preferred that you set up your Use statements accordingly.

```php
<?php

namespace MyApp\Subspace;
use \Nether;
```

To provide access to the Nether namespace without having to include the \
each time you want to access a class in the Nether namespace.

## Method Definitions.

Methods will be defined in PascalCase. The method name itself will be defined
nether the access keywords. This is to prevent your eyes from having to jump
left and right constantly while scrolling through the file. If you have
concerns with finding methods by doing something like doing a search for
"ion SetSomething" then you should instead famliarise yourself with the symbol
finding feature of your editor. Opening braces will be after the method
identifier. Methods will always have an access keyword. If the access keyword
was going to be omitted, then it will be unomitted with the keyword `public`.

Methods shall be declared as explicit as possible. Their arguments should have
their accepted types declared as well as the return type of the method. The
return type shall be placed nether the method.

Methods which return nothing, meaning, not an explicit NULL, will even have
their type declared as Void. Void methods and functions will not "just end"
they will include explicit return when they are done. Nullable types are
encouraged where they could make your API more simple to check result against.

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

If a method will take a lot of arguments, or has a handful of optional ones,
it is preferable that instead of a long argument list that that method accept
an object or array instead. Something like `Nether\Object` can be used to help
ensure population for optional arguments with enforced defaults. This eliminates
the constant nagging feeling of having forgot what order the arguments should
be presented in as long as you can recall what it needs.

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

You would not follow this naming convention if the split methods are going
to be reusable by many different processes. This section is mainly for
separation of concerns where the separated actions are useless on their
own.

To split a long method into smaller units of code, of which may not be
usuable on their own, it is your desgression at how to structure your methods
for your preferred method of unit testing. These reduced concern methods shall
be prefixed with the method name they are designed to work with, with the
descriptive action being separated by a netherscore in the method name.

```php
<?php

class Project {

	public function
	GetFileContents(String $Filename) {
	/*//
	@return StdClass | bool
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

When using chained methods and the line may potentially become unwieldy, then
each link in the chain will be placed on a new line at the same indention level
as the symbol the chain originates from. Calls like this will be isolated by
empty lines above and below them.

```php
<?php

$DB = new Nether\Database;

$Query = $DB->NewVerse()
->Select('table')
->Fields(['one','two','three'])
->Where(['five=six','seven=eight'])
->Limit(25);

$Result = $DB->Query($Query,$Input);
```

## HTML Templating

When working within the scope of an HTML template code blocks will be written
using their Alternative Syntax. Short tag echo will not be used.

```php
<?php if($Stuff): ?>
<h1><a href="<?php echo $URL ?>"><?php echo $Title ?></a></h1>
<?php endif; ?>
```

