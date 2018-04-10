# Pseudo #

Pseudo is a system for mocking PHP's PDO database connections. When writing unit tests for PHP applications, one frequently has the need to test code that interacts with a database. However, in the true spirit of a unit test, the database should be abstracted, as we can assume with some degree of certainty that things like network links to the database server, the database connection drivers, and the database server and software itself are "going to work", and they are outside the scope of our unit tests.

Enter Pseudo. Pseudo allows you to have "fake" interactions with a database that produce predefined results every time. This has 2 main advantages over actually interacting with a database. First, it saves having to write data fixtures in another format, ensuring the data schema availability, loading the fixtures in, and then later cleaing and resetting them between tests.  Second, and somewhat as a result of the first, tests can run *significantly* faster because they are essentially talking to an in-memory object structure rather than incurring all the overhead of connecting and interacting with an actual database.

## Theory of Operation

The general idea is that Pseudo implements all of the classes in the PDO system by inheriting from them and then overriding their methods. During your test, at the point where you would inject a PDO object into your data layer, you can now inject a Pseudo\Pdo object transparently, giving yourself 100% flexibility to control what your application now *thinks* is the database. In your unit test, you can express the mocks for your test in terms of SQL statements and arrays of result data.

### Simple Example
	<?php
	$p = new Pseudo\Pdo();
	$results = [['id' => 1, 'foo' => 'bar']];
	$p->mock("SELECT id FROM objects WHERE foo='bar'", $results);

	// now use this $p object like you would any regular PDO
	$results = $p->query("SELECT id FROM objects WHERE foo='bar'");
	while ($result = $results->fetch(PDO::FETCH_ASSOC)) {
		echo $result["foo"];  // bar
	}

### Supported features
The internal storage of mocks and results are associatve arrays. Pseudo attempts to implement as much of the standard PDO feature set as possible, so varies different fetch modes, bindings, parameterized queries, etc all work as you'd expect them to.

### Not implemented / wish-list items
* The transaction api is implemented to the point of managing current transaction state, but transactions have no actual effect
* Anything related to scrolling cursors has not been implemented, and this includes the fetch modes that might require them
* Pseudo can load and save serialized copies of it's mocked data, but in the future, it will be able to "record" a live PDO connection to a real database and then use that data to create mocks from your actual data
* Pseudo isn't strict-mode compatible, which means tests might fail due to unexpected errors with signatures and offsets, etc. (I'd happily accept a pull request to fix this!)

## Tests
Pseudo has a fairly robust test suite written with PHPUnit. If you'd like to run the tests, simply run `./vendor/bin/phpunit` in the root folder. The tests have no external library dependencies (other than phpunit) and should require no additional setup or bootstrapping to run.

Pseudo is also tested on Travis-CI
[![Build Status](https://secure.travis-ci.org/jimbojsb/pseudo.png?branch=master)](http://travis-ci.org/jimbojsb/pseudo)

## Requirements
Pseudo internals currently target PHP 5.4.0 and above. It has no external dependencies aside from the PDO extension, which seems rather obvious.

Pseudo is built and tested with error reporting set to ```E_ALL & ~(E_NOTICE | E_DEPRECATED | E_STRICT)```. If you are running in a stricter error reporting mode, your tests will most likely fail due to strict mode method signature violations. (This is on the known issues / to do list)
