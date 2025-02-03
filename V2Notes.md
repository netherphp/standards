# Filesystem Habits

## Problem
I want to delete a file but not really just in case there is some page
all of us forgot that was using it.

## Solution
Rename the file to start with a minus (-).

* widget.phtml
* -widget.phtml

Files that remain named with a leading minus for a length of time and
nobody has found something that broke should be assumed to be safe to
delete. Code should never directly reference a minused file. If it does
then that code is wrong.

## Problem
I want to designate a file as ultra important in some way, like maybe a shared
navigation for a specific site section. It is not in danger of being deleted I
just want it to seem more global or something while scrolling a directory of
files.

## Solution
Rename the file with two leading underscores (__). Files named in this manner
should be considered somewhat important for their context, like a global
navigation file. This is done to emulate the PHP behaviour where the built in
methods considered magical are prefixed with dual underscores.

* __nav.phtml
* index.phtml
* about.phtml

In this context it is safe to assume that __nav.phtml applies to and will be used
by the other templates in that directory.

