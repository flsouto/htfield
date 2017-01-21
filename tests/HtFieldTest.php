<?php

#mdx:h autoload
require_once('vendor/autoload.php');

#mdx:h RequireMyField hidden
require_once('tests/MyField.php');

use PHPUnit\Framework\TestCase;

/*
# HtField

## Overview

The HtField class is a base class for implementing all kinds of form fields, both widgets and non-widgets.
It is basically a wrapper for two things: 

- parameter resolution 
- tag attribute setting

For understanding these concepts more deeply I recommend you take a look at the two underlying libraries being used by this class:

- [FlSouto\Param](https://github.com/flsouto/param/)
- [FlSouto\HtAttrs](https://github.com/flsouto/htattrs)

## Installation

Install this library via composer

```
composer require flsouto/htfield
```

## Usage

As this is an abstract class, it is only useful if you want to build your own form field types.
Therefore in this document I will show you how you can extend it in order to implement a very simple widget.

### Defining a Simple Widget

Our example widget is going to be poor in functionality but will be enough for demonstrating the base API. 
We are going to call it 'MyField'. See its definition below:

#mdx:MyField

*/

class HtFieldTest extends TestCase{


/* 
### Rendering the Field

To render a field instance simply print it out:

#mdx:render -h:HtFieldAlias

The output will be:

#mdx:render -o

*/
	function testRender(){		
		#mdx:render
		$field = new MyField('email');
		#/mdx echo $field
		$this->expectOutputRegex('/input.*email/');
		echo $field;
	}

/*
### Changing the ID

By default, all fields have a default, random id. You can change it to a custom string by calling the attributes API.
See example below:

#mdx:changeId -h:HtFieldAlias,autoload -php

The output will be:

#mdx:changeId -o

*/
	function testChangeId(){
		#mdx:changeId
		$field = new MyField('email');
		$field->attrs(['id'=>'email_field']);
		#/mdx echo $field
		$this->expectOutputRegex("/input.*email_field/");
		echo $field;
	}
/*
### Retrieving the ID

Use the $field->id() method for that:

#mdx:idGetter idem

Outputs:

#mdx:idGetter -o

*/
	function testIdGetter(){
		#mdx:idGetter
		$field = new MyField('email');
		$field->attrs(['id'=>'email_field']);
		#/mdx echo $field->id()
		$this->assertEquals('email_field',$field->id());
	}

/* 
### The name attribute

The name is a special attribute that can only be set once via constructor.
However you can access it's value later by using the $field->name() getter:

#mdx:nameGetter idem

Outputs:

#mdx:nameGetter -o

*/
	function testNameGetter(){
		#mdx:nameGetter
		$field = new MyField('address');
		#/mdx echo $field->name()
		$this->assertEquals('address',$field->name());
	}

/* 
### Processing Input

The `$field->process()` method returns an object which contains two properties:

- **$field->output**: contains the value extracted from the specified source of input
- **$field->error**: contains any error messages occurred during the extraction process

But, before you can process anything you must specify the source of input. 
In other words, you must set the "context" from which the data is to be extracted.
See example:

#mdx:process idem

Outputs:

#mdx:process -o

#### Observations

- The context method can be chained just like any other setter method of this class.
- The process method accepts an optional context array, which, if provided, will be used instead of the one set by `$field->context()`.

*/
	function testProcess(){
		#mdx:process
		$field = new MyField('username');
		$field->context(['username'=>'Jack']);
		#/mdx echo $field->process()->output
		$this->assertEquals('Jack',$field->process()->output);
	}

/*
### Processing Namespaced Fields

A field can have a fully qualified name in the following form:

```
path[to][field_name]
```

This means that the input is expected to be found in a data structure like this:

```
Array(
	[path] => Array(
		[to] => Array(
			[field_name] => INPUT
		)
	)
)
```

Here is an example of this concept in action:

#mdx:namespaced idem

Output:

#mdx:namespaced -o

*/
	function testProcessNamespaced(){
		#mdx:namespaced
		$field = new MyField('user[contact][email]');
		$field->context(['user'=>['contact'=>['email'=>'user@domain.com']]]);
		#/mdx echo $field->process()->output
		$this->assertEquals('user@domain.com',$field->process()->output);
	}

/*
#### The $field->value() shortcut

Instead of writing the rather verbose statement `$field->process()->output` you can simply call `$field->value()` which has the same effect:

#mdx:value idem

Results in:

#mdx:value -o

*/
	function testValue(){
		#mdx:value
		$field = new MyField('description');
		$input = $field
			->context(['description'=>'This is just a test'])
			->value();
		#/mdx echo $input
		$this->assertEquals('This is just a test',$input);
	}

/* 
#### The $field->validate() shortcut

This is an alias to `$field->process()->error`:

#mdx:validate idem

#mdx:validate -o

*/
	function testValidate(){
		#mdx:validate
		$field = new MyField('name');
		$error = $field->required("Provide a name!")
			->context(['name'=>''])
			->validate();
		#/mdx echo $error
		$this->assertEquals('Provide a name!',$error);
	}

	
}