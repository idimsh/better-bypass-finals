Bypass Finals
=============

[![Downloads this Month](https://img.shields.io/packagist/dm/idimsh/better-bypass-finals.svg)](https://packagist.org/packages/idimsh/better-bypass-finals)
[![Build Status](https://travis-ci.org/idimsh/better-bypass-finals.svg?branch=master)](https://travis-ci.org/idimsh/better-bypass-finals)
[![License](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/idimsh/better-bypass-finals/blob/master/license.md)


#Introduction (idimsh)
This is a redistribution of the famous package [bypass-finals](https://github.com/dg/bypass-finals/) with minor modifications that includes:  
* making it inheritance ready, allowing subclasses of the main class to continue working.
* code style uses spaces instead of tabs.
* tests are not using Nette Tester, but PHPUnit

The original license remains untouched, and they are BSD and GNU both.  
The reason for creating this package is an un answered pull request. 


Introduction
------------

Removes final keywords from source code on-the-fly and allows mocking of final methods and classes.
It can be used together with any test tool such as PHPUnit, Mockery or [Nette Tester](https://tester.nette.org).


Installation
------------

The recommended way to install is through Composer:

```
composer require idimsh/better-bypass-finals --dev
```

It requires PHP version 7.1 and supports PHP up to 8.0.


Usage
-----

Simply call this:

```php
\idimsh\BypassFinals::enable();
```
or
```php
\idimsh\BypassFinalsCatcher::enable();
```

You need to enable it before the classes you want to remove the final are loaded. So call it as soon as possible,
preferably right after `vendor/autoload.php` in loaded.

Note that final internal PHP classes like `Closure` cannot be mocked.

You can choose to only bypass finals in specific files or directories:

```php
idimsh\BypassFinals::setWhitelist([
    '*/Nette/*',
]);
```

This gives you finer control and can solve issues with certain frameworks and libraries.  

There is also a PHP Unit listener available for usage inside `phpunit.xml` file, like:

 ```xml
 <phpunit bootstrap="vendor/autoload.php">
     <listeners>
         <listener class="idimsh\BypassFinal\Listener\BypassFinalListener"/>
     </listeners>
 </phpunit>
 ```
 
 ### The *Catcher* version
 The extension of the main class with the name:
 `\idimsh\BypassFinalsCatcher`
 Will catch syntax errors and echo the file name to STDERR. This is useful for tests that span thousand files, the default behaviour was a silent fatal parse error that does not report the file name.  
 
 There is also a listener that uses the catcher. 

```xml
<phpunit bootstrap="vendor/autoload.php">
    <listeners>
        <listener class="idimsh\BypassFinal\Listener\BypassFinalCatcherListener"/>
    </listeners>
</phpunit>
```
