# Exo // Next-Gen Eco Framework
### Classes
- [Entity](#exoentity)
- [Event](#trait-exoevent)
- [Exception](#exoexception)
- [Factory](#exofactory)
	- [Annotation](#exofactoryannotation), [Mapper](#exofactorymapper), [Singleton](#exofactorysingleton)
- [Logger](#exologger)
- [Map](#exomap)
- [Model](#exomodel)
- [Options](#exooptions)
	- [Singleton](#exooptionssingleton)
- [Share](#exoshare)
- [Validator](#exovalidator)

### Functions
- [bind()](#bind-string)
- [pa()](#pavalues-void)

# Classes

## `Exo\Entity`
Entity is an object helper.
```php
/**
 * @property string $name
 * @property int $level
 * @property bool $isActive
 * @property string $title
 */
class UserEntity extends \Exo\Entity
{
	// public $name; // not allowed because exists in getProps()

	// constructor is optional
	public function __construct(array $data = null)
	{
		parent::__construct($data); // must be called

		// can set default values here
		if(!$this->title)
		{
			$this->title = '[none]';
		}
	}

	// required, return [prop => validator, prop, ...]
	protected function getProps(): array
	{
		return [
			'name' => $this->validator()
				->alpha(true),
			'level' => $this->validator()
				->numeric(),
			'isActive' => $this->validator()
				->bool(),
			'title' // no validator
		];
	}
}
// usage
$entity = new UserEntity;
$entity->name = 'Shay';
$entity->level = 5;
$entity->isActive = true;
print_r($entity->toArray());
// Array ( [name] => Shay [level] => 5 [isActive] => 1 [title] => [none] )
print_r($entity->toArray(['name' => 1]));
// Array ( [name] => Shay )
print_r($entity->toArray(['name' => 0, 'title' => 0]));
// Array ( [level] => 5 [isActive] => 1 )

// constructor usage
$entity2 = new UserEntity([
	'name' => 'Bob',
	'level' => 2
]);
echo sprintf('Name: %s, Level: %d, Active: %d',
	$entity2->name,
	$entity2->level,
	$entity2->isActive
);
// throws exception:
// Assertion failed: "isActive" value is required (value: "")
```
### Methods
- `toArray(array $filter = null): array` - to array method
	- `$filter` - allows filtering fields
		- remove specific fields: `[field => 0, ...]`
		- include only specific fields: `[field => 1, ...]`
- `validator(): \Exo\Validator` - validator object getter




## `trait Exo\Event`
Event is an event helper.
```php
class User
{
	use \Exo\Event;

	// required
	protected static function &getEvents(): array
	{
		static $events = [];
		return $events;
	}

	public function signIn(int $id)
	{
		// sign in code here
		self::emitEvent('user.signIn', ['id' => $id]);
	}
}
// bind event(s) before User use
User::onEvent('user.signIn', function($args){
	echo 'User signed in, user ID: ' . $args['id'];
});
// usage
$user = new User;
$user->signIn(14);
// User signed in, user ID: 14
```
### Callable Chain
Multiple callables can be bound to the same event:
```php
User::onEvent('user.signIn', function($args){
	echo 'User signed in, user ID: ' . $args['id'];
});
User::onEvent('user.signIn', function(){
	echo 'User sign in detected';
});
// on event trigger:
// User signed in, user ID: 14
// User sign in detected
```
Returning `true` from any bound callable will interrupt the chain of callables:
```php
User::onEvent('user.signIn', function($args){
	echo 'User signed in, user ID: ' . $args['id'];
	return true; // stop chain
});
User::onEvent('user.signIn', function(){
	echo 'User sign in detected';
});
// on event trigger:
// User signed in, user ID: 14
```



## `Exo\Exception`
Exceptions can be handled using the `Exo\Exception` class:
```php
try
{
	(new MyClass)->badMethod();
}
catch(Exception | Throwable $th)
{
	\Exo\Exception::handle($th, function(array $info) use(&$th){
		// add some more info (optional)
		$info['file'] = $th->getFile();
		$info['line'] = $th->getLine();
		logRecord($info); // log the exception or something

		// output and stop
		print_r($info);
		exit;
		// --or-- continue to throw exception
		throw $th;
	});
}
```



## `Exo\Factory`
The Exo factory is a factory helper that can be inherited.
```php
/**
 * @method Service service()
 */
class App extends \Exo\Factory
{
	private static $classes = [
		'service' => 'Service'
	];

	public static function &getClasses(): array
	{
		// merge with Exo classes (optional)
		$classes = self::$classes + parent::getClasses();
		return $classes;
	}
}
// helper function (optional)
function app(): App
{
	return App::getInstance();
}
// usage
app()->service()->doSomething();
```
### Methods
- `logger(): \Exo\Logger`
- `map(array $map = null): \Exo\Map`
- `options(array $options = null): \Exo\Options`
- `request(): \Exo\Request`
- `store(): \Exo\Store`
- `validator(): \Exo\Validator`



## `Exo\Factory\Annotation`
Annotation is a class loading helper that utilizes class annotations.
```php
/**
 * @property \Model\Item $item
 * @property \Model\User $user
 */
class Model extends \Exo\Factory\Annotation
{
	// required
	protected static function &getClasses(): array
	{
		static $classes = []; // empty array
		return $classes;
	}
	 // required
	protected static function &getInstances(): array
	{
		static $instances = []; // empty array
		return $instances;
	}
}
// usage
$user = Model::getInstance()->user->get($userId);
$price = Model::getInstance()->item->getPrice($itemId);
```
### Singleton Pattern
Use the singleton pattern in factory classes by inheriting the `Exo\Factory\Singleton` class:
```php
class User extends \Exo\Factory\Singleton {}
// now this call will return \Model\User::getInstance()
$user = Model::getInstance()->user->get($userId);
```
### Inheritance Chain
```php
/**
 * @property \Database\MySql\Db1\Table1 $table1
 */
class Db1 extends \Exo\Factory\Annotation {}
/**
 * @property \Database\MySql\Db2\Table1 $table1
 */
class Db2 extends \Exo\Factory\Annotation {}
/**
 * @property \Database\MySql\Db1 $db1
 * @property \Database\MySql\Db2 $db2
 */
class Database extends \Exo\Factory\Annotation {}
// usage
Database::getInstance()->db1->table1->insert([...]);
Database::getInstance()->db2->table1->insert([...]);
```
### Function Helper
Helper function example:
```php
function model(): Model
{
	return Model::getInstance();
}
// usage
$user = model()->user->get($userId);
```



## `Exo\Factory\Mapper`
Mapper is a class loading helper.
```php
/**
 * @method \Factory\Item item(int $itemId)
 * @method \Factory\User user(int $userId)
 */
class Factory extends \Exo\Factory\Mapper
{
	// required
	protected static function &getClasses(): array
	{
		static $classes = [
			'item' => '\Factory\Item',
			'user' => '\Factory\User'
		 ];
		return $classes;
	}
	// required
	protected static function &getInstances(): array
	{
		static $instances = []; // empty array
		return $instances;
	}
}
// usage
$price = Factory::getInstance()->item($itemId)->getPrice();
$user = Factory::getInstance()->user($userId)->get();
```
### Singleton Pattern
Use the singleton pattern in factory classes by inheriting the `Exo\Factory\Singleton` class:
```php
class User extends \Exo\Factory\Singleton {}
// now this call will return \Factory\User::getInstance()
$user = Factory::getInstance()->user($userId)->get();
```
### Helper Function
Helper function example:
```php
function factory(): Factory
{
	return Factory::getInstance();
}
// usage
$user = factory()->user($userId)->get();
```



## `Exo\Factory\Singleton`
Singleton is a singleton pattern helper.
```php
class Session extends \Exo\Factory\Singleton
{
	// required
	protected static function &getInstances(): array
	{
		static $instances = []; // empty array
		return $instances;
	}
}
// usage
$sessId = Session::getInstance()->sessionId();
```
### Helper Function
Helper function example:
```php
function session(): Session
{
	return Session::getInstance();
}
$sessId = session()->sessionId();
```



## `Exo\Logger`
Logger is a logging helper.
```php
// first setup log handler
// most basic handler, store log records in array:
$logHandler = new \Exo\Logger\Handler\Runtime;
\Exo\Logger::handler($logHandler); // register

// simple helper function for this example
function logger(): \Exo\Logger { return new \Exo\Logger; }

// some code
logger()->user->debug('User authenticated', ['id' => $userId]); // channel "user"
// more code
logger()->session->debug('Session started'); // channel "session"
// more code
if($fatal)
{
	logger()->critical('Database connection failed', ['error' => $dbError]); // no channel
}
// get and output log
print_r( $logHandler->close() );
```
Example custom log handler:
```php
class MyLogHandler implements \Exo\Logger\HandlerInterface
{
	protected $level;

	public function __construct(int $level = \Exo\Logger::LEVEL_DEBUG)
	{
		$this->level = $level;
	}

	public function close()
	{
		// do something like output log or close connection
	}

	public function isHandling(\Exo\Logger\Record $record): bool
	{
		return $record->level >= $this->level;
	}

	public function write(\Exo\Logger\Record $record)
	{
		// do something like write to file or DB table
	}
}
```
### Methods
- `critical(?string $message, ...$context): \Exo\Logger` - critical log record
- `debug(?string $message, ...$context): \Exo\Logger` - debug log record
- `error(?string $message, ...$context): \Exo\Logger` - error log record
- `static handler(\Exo\Logger\Handler $handler)` - add log handler
- `info(?string $message, ...$context): \Exo\Logger` - info log record
- `warning(?string $message, ...$context): \Exo\Logger` - warning log record



## `Exo\Map`
Map is a helper class for handling arrays. Map implements `Countable` and `Iterator`.
### Methods
- `__construct(array $map)` - *overridable*
- `clear($key)` - clear
- `count()` - get count
- `get($key)` - getter
- `has($key)` - check if exists
- `hasValue($value)` - check if value exists
- `isEmpty(): bool` - check it map is empty
- `merge(array $map)` - merge with another map
- `set($key, $value)` - setter
- `toArray(): array` - get as array



## `Exo\Model`
Model is a model helper class that can be used with `Exo\Entity` objects.
> For reference see [`Exo\Entity`](#exoentity) example above
```php
class UserModel extends \Exo\Model
{
    // required for using entity
    const ENTITY = 'UserEntity';
    // create example
    public function create(array $document): bool
    {
        return app()->store->users->insert(
            $this->entity($document)->toArray()
        );
    }
    // array of entities example
    public function createMany(array $documents): int
    {
        return app()->store->users->insertMany(
            $this->entityArray($documents)
            // possible to use filter like:
            // $this->entityArray($documents, ['name' => 1])
        );
    }
}
```



## `Exo\Options`
Options is a helper class for handling options with validation.
```php
namespace Canvas;
class Rectangle
{
	private $options;

	public function __construct(array $options)
	{
		$this->options = new \Exo\Options;

		// register options (before setting)
		$this->options->option('height')
			->numeric(); // must be numeric (+required)
		$this->options->option('width')
			->numeric();
		$this->options->option('invert')
			->bool()
			->optional(); // not required
		$this->options->set('invert', false); // set default

		// set options (after registering)
		$this->options->set($options);
	}

	public function getOptions(): array
	{
		return $this->options->toArray();
	}

	// getters example
	public function render()
	{
		echo $this->draw(
			$this->options->get('height'),
			$this->options->get('width'),
			$this->options->get('invert')
		);
	}
}
```
Usage example:
```php
use Canvas\Rectangle;
$rec = new Rectangle([
	'height' => 300,
	'width' => 500
]);

print_r($rec->getOptions());
// Array ( [invert] => [height] => 300 [width] => 500 )
```
### Methods
- `__construct(array $options)` - *overridable*
- `get(string $key): mixed` - getter
- `getValidators(): array` - get all options validator objects
- `has(string $key): bool` - check if exists
- `merge(\Exo\Options $options)` - merge options
- `option(string $key): \Exo\Validator` - register option with validator
- `required(array $keys)` - set option keys as required
- `set(array|string $key)` - set option(s)
- `toArray(): array` - get options as array `[key => value, ...]`



## `Exo\Options\Singleton`
The singleton options class is a helper class for hanlding options with validation.
```php
namespace Canvas;
class Options extends \Exo\Options\Singleton
{
	const HEIGHT = 'maxh';
	const WIDTH = 'maxw';
	// required
	protected function __init(): void
	{
		$this->option(self::HEIGHT)
			->digit();
		$this->option(self::WIDTH)
			->digit();
		// set defaults
		$this->set([
			self::HEIGHT => 600,
			self::WIDTH => 800
		]);
	}
}
// usage in class example
class Rectangle
{
	// more code
	public function render()
	{
		echo $this->draw(
			Options::get(Options::HEIGHT),
			Options::get(Options::WIDTH)
		);
	}
}
```
Usage:
```php
use Canvas\Options;
use Canvas\Rectangle;
// set options
Options::set(Options::HEIGHT, 300);
Options::set(Options::WIDTH, 500);
(new Rectangle)->render();
```
### Methods
- `get(string $key)` - getter
- `has(string $key): bool` - check if exists
- `merge(\Exo\Options $options)` - merge options
- `option(string $key): \Exo\Validator` - register option with validator
- `required(array $keys)` - set option keys as required
- `set(array|string $key, $value = null)` - set option(s)
- `toArray(): array` - get options as array `[key => value, ...]`

### Static Methods
- `get(string $key)` - getter
- `has(string $key)` - check if exists
- `merge(\Exo\Options $options)` - merge options
- `set(array|string $key, $value = null)` - set option(s)
- `toArray(): array` - get options as array `[key => value, ...]`


## `Exo\Share`
Share is a global key/value helper.
```php
use Exo\Share;
Share::getInstance()->set('user', new User(14));
// more code
$userLevel = Share::getInstance()->get('user')->getLevel();
// can also use props:
Share::getInstance()->user = new User(14);
$userLevel = Store::getInstance()->user->getLevel();
```
### Methods
- `clear(string $key)` - clear key
- `get(string $key)` - getter
- `has(string $key): bool` - check if key exists
- `set(string $key, $value)` - setter



## `Exo\Validator`
Validator is a validation helper.
```php
use \Exo\Validator;
$userName = 'Bob';
$userAge = '';
(new Validator('name'))
	->assert($userName);
(new Validator('age'))
	->numeric()
	->assert($userAge);
// throws exception:
// Assertion failed: "age" must be numeric, value is required (value: "")

// all values are considered required unless set as optional
(new Validator('email'))
	->email()
	->optional() // not required
	->assert(null); // no exception
```
Use custom validation exception messages:
```php
(new Validator('age'))
	->numeric()->message('Invalid age')
	->required()->message('You must enter your age')
	->assert($userAge);
// throws exception:
// Assertion failed: "age" Invalid age, You must enter your age (value: "")
```
Use callback with `assert()` method:
```php
(new Validator('age'))
	->numeric()->message('Invalid age')
	->required()->message('You must enter your age')
	->assert($userAge, function(array $validationMessages){
		handleValidationErrors($validationMessages);
		// return true to halt and not throw validation exception
		return true;
	});
```
Use custom rule:
```php
class MyRule implements \Exo\Validator\RuleInterface
{
	protected $message = 'does not equal "validValue"';
	public function getMessage(): string { return $this->message; }
	public function setMessage(string $message): void { $this->message = $message; }
	public function validate($value): bool { return $value === 'validValue'; }
}
// usage
(new Validator('value'))
	->rule(new MyRule)
	->assert('badValue');
// throws exception:
// Assertion failed: "value" does not equal "validValue" (value: "badValue")
```
Usage with `validate()` method instead:
```php
$isValid = (new Validator('age'))
	->numeric()
	->validate($userAge)
if(!$isValid) // do something
```
### Rules
- `alnum(bool $allowWhitespaces = false)` - must contain only alphanumeric characters
- `alpha(bool $allowWhitespaces = false)` - must contain only alphabetic characters
- `array(int $maxDepth = 1)` - must be an `array` with depth of $maxDepth
- `between($min, $max)` - must be between both values
- `bool()` - must be bool
- `contains($containsValue, bool $caseSensitive = true)` - must contain value
- `digit()` - must be a digit
- `email()` - must be a valid email address
- `hash(string $knownHash)` - hashes must be equal
- `in(...$list)` - must be in list
- `ipv4()` - must be valid IPv4 address
- `ipv6()` - must be valid IPv6 address
- `json()` - must be a valid JSON
- `length(int $min = null, int $max = null, int $exact = null)` - must be a specific length
- `notEmpty()` - must not be empty
- `match($compareValue, bool $caseSensitive = true)` - values must be equal
- `notIn(...$list)` - must not be in list
- `numeric()` - must be numeric
- `optional()` - value is optional
- `password(string $hash)` - passwords must be equal
- `regex(string $regex)` - must match regular expression
- `required()` - value is required
- `stdClass()` - must be instance of `stdClass`
- `url()` - must be valid URL
### Methods
- `assert($value, callable $callback)` - throws exception if validation fails
- `getMessage()` - get first validation message after validation
- `getMessage(): array` - get all validation messages after validation
- `getName()` - get name
- `message(string $message): \Exo\Validator` - set validation message for last rule
- `rule(\Exo\Validator\RuleInterface $rule): \Exo\Validator` - add custom rule
- `validate($value): bool` - validate value



# Functions

## `bind(): string`
Formatted string helper.
```php
// scalar value
$str = bind('Invalid ID: {$1}', 5);
// multiple scalar values
$str = bind('ID: {$1}, Name: {$2}', 5, 'Shay');

// indexed array
$str = bind('ID: {$0}, Name: {$1}', [5, 'Shay']);
// associative array
$str = bind('ID: {$id}, Name: {$name}', ['id' => 5, 'name' => 'Shay']);

// object
$user = new stdClass; $user->id = 5; $user->name = 'Shay';
$str = bind('ID: {$id}, Name: {$name}', $user);

// indexed multidimensional array
$str = bind('ID: {$0}, Name: {$1}', [[5, 'Shay'], [6, 'Max']]);
// associative multidimensional array
$str = bind('ID: {$id}, Name: {$name}',
	[['id' => 5, 'name' => 'Shay'], ['id' => 6, 'name' => 'Max']]);

// callback example
$str = bind('ID: {$id}, Name: {$name}', ['id' => 5, 'name' => 'Shay'],
	function($object){
		$object->id *= 1000;
		$object->name = strtoupper($object->name);
		return $object;
	}
);
```
Any depth beyond the allowed depth of two is auto-serialized, example:
```php
$str = bind('ID: {$id}, Name: {$name}, Roles: {$roles}', [
		['id' => 5, 'name' => 'Shay', 'roles' => ['admin', 'editor']],
		['id' => 6, 'name' => 'Max', 'roles' => ['editor', 'viewer', 'guest']]
	], function($object){
		// convert to something useful
		$object->roles = implode(', ', unserialize($object->roles));
		return $object;
	});
// ID: 5, Name: Shay, Roles: admin, editor
// ID: 6, Name: Max, Roles: editor, viewer, guest
```



## `pa(...$values): void`
HTML and CLI friendly printer for all PHP types.
```php
pa(1, ['one'], new stdClass); // print all values
```

