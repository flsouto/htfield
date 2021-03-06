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

```php
<?php
use FlSouto\HtField;
require_once('vendor/autoload.php');

class MyField extends HtField{

	// Set field as required
	function required($errmsg){
		$this->param->filters()->required($errmsg);
		return $this;
	}

	// It is mandatory to implement the 'render' method
	function render(){
		echo "<input ".$this->attrs." />";
	}

}

```


### Rendering the Field

To render a field instance simply print it out:

```php
<?php
require_once('vendor/autoload.php');

$field = new MyField('email');

echo $field;
```

The output will be:

```
<input id="field_5918f4b6a4df9" name="email" />
```


### Changing the ID

By default, all fields have a default, random id. You can change it to a custom string by calling the attributes API.
See example below:

```php

$field = new MyField('email');
$field->attrs(['id'=>'email_field']);

echo $field;
```

The output will be:

```
<input id="email_field" name="email" />
```


### Retrieving the ID

Use the $field->id() method for that:

```php

$field = new MyField('email');
$field->attrs(['id'=>'email_field']);

echo $field->id();
```

Outputs:

```
email_field
```


### The name attribute

The name is a special attribute that can only be set once via constructor.
However you can access it's value later by using the $field->name() getter:

```php

$field = new MyField('address');

echo $field->name();
```

Outputs:

```
address
```


### Processing Input

The `$field->process()` method returns an object which contains two properties:

- **$field->output**: contains the value extracted from the specified source of input
- **$field->error**: contains any error messages occurred during the extraction process

But, before you can process anything you must specify the source of input. 
In other words, you must set the "context" from which the data is to be extracted.
See example:

```php

$field = new MyField('username');
$field->context(['username'=>'Jack']);

echo $field->process()->output;
```

Outputs:

```
Jack
```

#### Observations

- The context method can be chained just like any other setter method of this class.
- The process method accepts an optional context array, which, if provided, will be used instead of the one set by `$field->context()`.


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

```php

$field = new MyField('user[contact][email]');
$field->context(['user'=>['contact'=>['email'=>'user@domain.com']]]);

echo $field->process()->output;
```

Output:

```
user@domain.com
```


#### The $field->value() shortcut

Instead of writing the rather verbose statement `$field->process()->output` you can simply call `$field->value()` which has the same effect:

```php

$field = new MyField('description');
$input = $field
	->context(['description'=>'This is just a test'])
	->value();

echo $input;
```

Results in:

```
This is just a test
```


#### The $field->validate() shortcut

This is an alias to `$field->process()->error`:

```php

$field = new MyField('name');
$error = $field->required("Provide a name!")
	->context(['name'=>''])
	->validate();

echo $error;
```

```
Provide a name!
```
