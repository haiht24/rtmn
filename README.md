MostCoupon developer guideline
==========

* [Environments, domains, Git, Github](#git)
* [Coding conventions](#code-conventions)
* [Dependencies / libraries](#libs)
* [Frontend](https://github.com/MCCorp/MostCoupon/tree/master/frontend)
* [API](https://github.com/MCCorp/MostCoupon/tree/master/api)
* [Portal](https://github.com/MCCorp/MostCoupon/tree/master/portal)

<a id="git"></a>Environments, domains, Git, Github
--------------------
    
<table>
  <tr>
    <th>Environment</th>
    <th>Deploy strategy</th>
    <th>Git banch</th>
    <th>URL</th>
  </tr>
  <tr>
    <td>Staging</td>
    <td>Push hook on Repo</td>
    <td><a href="https://github.com/MCCorp/MostCoupon">master</a></td>
    <td><a href="http://dev.mccorp.co.com/">Click here</a></td>
  </tr>
  <tr>
    <td>Production</td>
    <td>Manual</td>
    <td><a href="https://github.com/MCCorp/MostCoupon/tree/production">production</a></td>
    <td><a href="https://mostcoupon.com">mostcoupon.com</a></td>
  </tr>
  <tr>
    <td>Jira Agile</td>
    <td></td>
    <td></td>
    <td><a href="http://mccorp.atlassian.net/">Click here</a></td>
  </tr>
  <tr>
    <td>PHPPgAdmin</td>
    <td></td>
    <td></td>
    <td><a href="http://mccorp.co.com/phpPgAdmin/">Click here</a></td>
  </tr>
  <tr>
    <td>PHPMyAdmin</td>
    <td></td>
    <td></td>
    <td><a href="http://mccorp.co.com:1111/">Click here</a></td>
  </tr>
  <tr>
    <td>Jenkins</td>
    <td></td>
    <td></td>
    <td><a href="http://mccorp.co.com:8080/">Click here</a></td>
  </tr>
</table>

<a id="code-conventions"></a> Coding conventions
--------------------

Programmers contributing to this project should follow a few simple code conventions to increase code readability and maintainability.

### Indentation
In Eclipse go to _Window->Preferences->PHP->Code Style->Formatter_ and alter the properties as follows:

* **Tab policy:** Spaces

* **Indentation size:** 4

* **Tab size:** 4

* **Default indentation for wrapped lines:** 1

* **Default indentation for array initializers:** 1

### Control Structures
Always use curly brackets in control structures, even if they are not needed. They increase the readability of the code, and they give you fewer logical errors.

 ```php
 if ((expr_1) || (expr_2)) {
	 // action_1;
 } elseif (!(expr_3) && (expr_4)) {
	 // action_2;
 } else {
	 // default_action;
 }
 ```
### Spaces
Always use spaces before and after operators.

 ```php
	$var1 = ($var2 + $var3) / 6;
	$arr1 = array('key' => 'value');
 ```
 	
### Visibility
Use PHP5`s private and protected keywords (public, protected, private). Additionally, protected method or variable names start with a single underscore ("_").

 ```php
 class Test {
	private $_foo;

	protected function _bar() {
	    /*...*/
	}

	public function baz(Test $other) {
	    /*...*/
	}
 }
 ```
	
### Naming convention
#### Functions
Write all functions in camelBack:

 ```php
	public function longFunctionName() {
		/*...*/
	}
 ```
 
#### Classes
Class names should be written in CamelCase, for example:

 ```php
	class ExampleClass {
		/*...*/
	}
 ```
 	
#### Variables
Variable names should be as descriptive as possible and should be written in camelBack in case of multiple words.

 ```php
	$user = 'John';
	$listOfUsers = array('John', 'Hans', 'Arne');
 ```
 
This code convetions are inspired by the [CakePHP coding conventions](http://book.cakephp.org/2.0/en/contributing/cakephp-coding-conventions.html).

### Documentation and comments
All comments and documentation should be written in English, and should in a clear way describe the commented block of code.

Each method should be documented, at least with a short phrase. Documentation should use the [PhpDoc](http://www.phpdoc.org/) syntax:

 ```php
	/**
	 * Foo function
 	*/
	public function foo() {
		/*...*/
	}
 ```

<a id="libs"></a> Dependencies / libraries
--------------------

Third party dependencies are managed through [Composer](http://getcomposer.org/). To add a dependency find the package on [Packagist](https://packagist.org/) and add it to ```api/app/composer.json``` or ```frontend/app/composer.json```.

