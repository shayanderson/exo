# Exo // Next-Gen Eco Framework
### [Classes](#classes-1)
- App
	- [Cli](#exoappcli), [Env](#exoappenv), [Request](#exoapphttprequest), [Response](#exoapphttpresponse)
- [Entity](#exoentity)
- [Event](#trait-exoevent)
- [Exception](#exoexception)
- [Factory](#exofactory)
	- [Annotation](#exofactoryannotation), [Dynamic](#exofactorydynamic), [Mapper](#exofactorymapper), [Singleton](#exofactorysingleton)
- [Logger](#exologger)
- [Map](#exomap)
- [Model](#exomodel)
- [Options](#exooptions)
- [Share](#exoshare)
- [Validator](#exovalidator)

### [Functions](#functions-1)
- [bind()](#bind-string), [debug()](#debugmessage--null-context--null-exologger), [env()](#envstring-key-default), [logger()](#loggerstring-channel---exologger), [pa()](#pavalues-void), [share()](#sharestring-key-value), [token()](#tokenint-length--32-string)

# Classes

## `Exo\App\Cli`
`Cli` is a CLI helper. The `Exo\Factory::cli()` will only return a `Exo\App\Cli` object if used with the CLI, all non-CLI usage will return `null`.
> These examples use the `Exo\Factory` helper function [`app()`](#exofactory).
```php
// Example usage from CLI:
// php index.php myCommand debug x=123 y=abc
print_r( app()->cli()->getArgs() ); // print all args
// Array ( [SCRIPT] => index.php [COMMAND] => myCommand [debug] => 1 [x] => 123 [y] => abc )

// getters
var_dump( app()->cli()->get('COMMAND') ); // string(9) "myCommand"
var_dump( app()->cli()->get('x') ); // string(3) "123"

// check if keys exist
var_dump( app()->cli()->has('x') ); // bool(true)
var_dump( app()->cli()->has('bad') ); // bool(false)
```
By default the `SCRIPT` and `COMMAND` keys are automatically set based on the `php [SCRIPT] [COMMAND] ...options` pattern. This default pattern can be changed by using the `map()` method, example using the same command in the example above:
```php
// override default map: Array ( [0] => SCRIPT [1] => COMMAND )
app()->cli()->map([
	0 => '_self_'
]);
// ...
print_r( app()->cli()->getArgs() );
// Array ( [_self_] => index.php [myCommand] => 1 [debug] => 1 [x] => 123 [y] => abc )
```
### Output
A CLI output helper can be used like:
```php
app()->cli()->output('Console message');
// outputs:
// Console message
```
Arrays are supported:
```php
app()->cli()->output(['1', '2', '3']);
// outputs:
// 1
// 2
// 3
```
Output messages on the same line:
```php
app()->cli()->output('First line');
app()->cli()->output()->prepend('Second');
	app()->cli()->output(' line');
app()->cli()->output('Third line');
// outputs:
// First line
// Second line
// Third line
```
An output buffer can be used:
```php
app()->cli()->output()->enableBuffering();
app()->cli()->output('one');
app()->cli()->output('two');
print_r( app()->cli()->output()->buffer() );
// Array ( [1] => one [2] => two )
```
Output using color:
```php
// output "text" (in green color)
app()->cli()->output()->colorGreen('text');
```
Output using indent:
```php
app()->cli()->output()->indent('text');
// multiple indents
app()->cli()->output()->indent()->indent('text');
```
Using other methods (like colors and indent) with the `prepend()` method:
```php
// the prepend() method must always be called last, example:
app()->cli()->output()->colorGreen()->prepend('some');
app()->cli()->output()->colorGreen(' text');
// outputs: "some text" (in green color)
```
### Confirm
Confirm example:
```php
app()->cli()->ouput('Continue?');
if( !app()->cli()->confirm('y') )
{
	exit;
}
```
### Methods
- `confirm(string $allow): bool` - confirm method
- `get(string $key)` - getter
- `getArgs(): array` - get all args
- `getMap(): array` - args map getter
- `has(string $key): bool` - check if key exists
- `map(array $map)` - args map setter
- `output($message): \Exo\App\Cli\Output` - output helper
### Output Methods
- `buffer(): array` - buffer getter
- `enableBuffering()` - enable buffering
- `line()` - print empty line
- `indent($message = null)` - indent
- `output($message)` - print message
- `prepend($message)` - print without newline
#### Output Color Methods
Color methods are: `colorBlue()`, `colorCyan()`, `colorGray()`, `colorGreen()`, `colorMagenta()`, `colorRed()`, `colorYellow()`

Light color methods are: `colorLightBlue()`, `colorLightCyan()`, `colorLightGray()`, `colorLightGreen()`, `colorLightMagenta()`, `colorLightRed()`, `colorLightYellow()`



## `Exo\App\Env`
`Env` is an application environment variables helper. Keys are case-sensitive.
> This example uses the helper function [`env()`](#envstring-key-default) and the `Exo\Factory` helper function [`app()`](#exofactory).

Example `.env` file:
```
DB_USER=myuser
DB_PWD=secret
```
Example usage:
```php
// load from file
app()->env()->load('/path/to/.env');

$dbUser = env('DB_USER'); // myuser
$dbPassword = env('DB_PWD'); // secret
// use default value if variable does not exist
$dbName = env('DB_NAME', 'default'); // default
// for critical env variables invalid key exception can be used:
$dbHost = env('DB_HOST', null, /* throw exception */ true);
// Exo\App\Exception\InvalidKeyException exception thrown: Invalid key "DB_HOST"
```
PHP environment variables from `$_ENV` (prefixed with `ENV.`) and `$_SERVER` (prefixed with `SERVER.`) are also accessible, example:
```php
$httpHost = env('SERVER.HTTP_HOST');
```
### Methods
- `get(string $key, $defaultValue = null, bool $invalidKeyException = false)` - getter
- `has(string $key): bool` - check if key exists
- `load(string $path)` - load file
- `toArray(array $filter = null): array` - to array



## `Exo\App\Http\Request`
`Request` is an HTTP request helper.
> These examples use the `Exo\Factory` helper function [`app()`](#exofactory).

Example `POST` request:
```php
if(app()->request()->isMethod('POST'))
{
	$name = app()->request()->input('name')->string();
	if(app()->request()->input('email')->has())
	{
		$email = app()->request()->input('email')->email();
	}
	// with validation example
	$username = app()->request()->input('username')
		->validator(
			app()->validator()
				->string()
				->alnum()
		)
		->string();
}
```
Example `GET` request:
```php
// using request: /?id=5&name=Shay
print_r([
	'id' => app()->request()->query('id')->integer(),
	// use "default" as default value if query "name" does not exist
	'name' => app()->request()->query('name', 'default')->string()
]);
// Array ( [id] => 5 [name] => Shay )
```
Session in request example:
```php
app()->session()->set('user.id', 5); // creates session data: [user => [id => 5]]
// ...
if(app()->session()->has('user.id'))
{
	$userId = app()->session()->get('user.id');
}
```
Session flash can be used to store short-term data where the data is available from when set through the following request, example:
```php
app()->session()->flash()->set('loginError', 'Invalid username');
// redirect, then output message
echo app()->session()->flash()->get('loginError');
// message is no longer available on next request
```
Cookie in request example:
```php
if(app()->request()->cookie('myCookie')->has())
{
	var_dump( app()->request()->cookie('myCookie')->string() );
}
```
### Methods
- `body(bool $convertHtmlEntities = true): string` - request body raw data getter
- `contentType(): string` - content-type getter
- `cookie(string $key, $default = null): Request\Cookie` - cookie input object getter
- `hasHeader(string $key): bool` - check if header key exists
- `header(string $key): string` - header value getter
- `headers(): array` - get all headers
- `host(): string` - HTTP host value getter, ex: `www.example.com`
- `input(string $key, $default = null): Request\Input` - input (POST) object getter
- `ipAddress(): string` - IP address getter
- `isContentType(string $contentType): bool` - validate request content-type
- `isMethod(string $method): bool` - validate request method
- `isSecure(): bool` - check if request is secure (HTTPS)
- `json(bool $returnArray = false)` - JSON request payload helper
- `method(): string` - request method getter
- `path(): string` - path getter, ex: `/the/path`
- `pathWithQueryString(): string` - path with query string getter, ex: `/the/path?x=1`
- `port(): int` - port getter
- `query(string $key, $default = null): Request\Query` - query (GET) input object getter
- `queryString(): string` - query string getter, ex: `x=1&y=2`
- `scheme(): string` - URI scheme getter, ex: `http`
- `session(): Request\Session` - session object getter
- `uri(): string` - URI getter, ex: `http://example.com/example?key=x`
### Input Methods
Input methods include methods for request input objects: `Cookie`, `Input` and `Query`.
- `email()` - value getter, sanitize as email
- `float()` - value getter, sanitize as float
- `has(): bool` - check if key exists
- `integer()` - value getter, sanitize as integer
- `string()` - value getter, sanitize as string
- `url()` - value getter, sanitize as URL
- `validator(\Exo\Validator\AbstractType $validator): AbstractInput` - validator setter
### Session Methods
Session methods `clear()`, `get()`, `has()` and `set()` all use dot notation for keys, for example: `set('user.isActive', 1)` equals: `[user => [isActive => 1]]`
- `clear(string $key)` - clear a key
- `static cookieOptions(array $options)` - set cookie options
	- default options are: `['lifetime' => 0, 'path' => '/', 'domain' => '', 'secure' => false, 'httponly' => false]`
- `destroy()` - destroy a session
- `get(string $key)` - value getter
- `has(string $key): bool` - check if key exists
- `isSession(): bool` - check if session exists
- `set(string $key, $value)` - key/value setter
- `toArray(): array` - session array getter



## `Exo\App\Http\Response`
`Response` is an HTTP response helper.
> These examples use the `Exo\Factory` helper function [`app()`](#exofactory).
```php
// set: header, status code and content type:
app()->response()
	->header('X-Test', 'abc')
	->statusCode( app()->response()::HTTP_OK )
	->contentType( app()->response()::CONTENT_TYPE_APPLICATION_JSON );
```
### Methods
All methods return `Exo\App\Http\Response`, unless otherwise stated.
- `cacheOff()` - disable cache using cache-control
- `contentType(string $contentType)` - content-type setter
- `cookie($key, $value, $expires, $path, $domain, $secure, $httpOnly): bool` - cookie setter
- `cookieClear(string $key, string $path = '/'): bool` - remove cookie
- `header(string $key, $value)` - header setter
- `headerClear(string $key)` - remove header key
- `headers(array $headers)` - headers setter using array
- `json($data): void` - respond with JSON and `Content-type: application/json` in headers
- `redirect(string $location, bool $statusCode301 = false): void` - send redirect
- `statusCode(int $code)` - status code setter



## `Exo\Entity`
Entity is an object helper.
```php
/**
 * @property int $id
 * @property string $name
 * @property bool $isActive
 */
class UserEntity extends \Exo\Entity
{
	// public $name; // not allowed because registered as property("name")

	// constructor is optional
	public function __construct(array $data = null)
	{
		parent::__construct($data); // must be called
	}

	// required (abstract method)
	protected function register()
	{
		// registered properties
		$this->property('id')
			->validator()
			->number();
		$this->property('name')
			->validator()
			->string()
			->alpha(true);
		$this->property('isActive', true) // set default value: true
			->validator()
			->boolean()
			->type();
	}
}
// usage
$entity = new UserEntity(['id' => 5]);
// can also use props as setters:
$entity->name = 'Shay';
// use props as getters:
$name = $entity->name;
print_r($entity->toArray());
// Array ( [id] => 5 [name] => Shay [isActive] => 1 )

// toArray() supports filters:
print_r($entity->toArray(['name' => 1]));
// Array ( [name] => Shay )
print_r($entity->toArray(['name' => 0, 'title' => 0]));
// Array ( [id] => 5 )

// assertion/validation example
$entity->id = null;
print_r($entity->toArray());
// throws exception: Assertion failed: "UserEntity.id" must be a number (value: [null])

// single property assertion example
$entity->assert('id', null);
// throws exception: Assertion failed: "UserEntity.id" must be a number (value: [null])

// single property validation example
var_dump($entity->validate('id', null)); // false
var_dump($entity->validate('id', 'Shay')); // true
```
The `apply()` method can be used to apply a callback to a property value, example:
```php
$this->property('name')
	->apply(function($name){
		return strtoupper($name);
	})
	->validator() // validator() must be called after all other property() methods
	->string();
// ...
$entity = new UserEntity(['name' => 'shay']);
echo $entity->name; // SHAY
```
The `bind()` method can be used to bind an external Entity reference to objects or arrays, example:
```php
class UserOptionsEntity extends \Exo\Entity
{
	protected function register()
	{
		$this->property('theme')
			->validator()
			->string()
			->allowed(['light', 'dark']);
	}
}

// in the UserEntity class bind the reference
class UserEntity extends \Exo\Entity
{
	protected function register()
	{
		// ...
		$this->property('options')
			->bind(new UserOptionsEntity)
			->validator()
			->object();
		// ...
	}
}
```
The Property `voidable()` method allows a property to be missing from the entity. This differs from the validator rule `optional` because optional requires the property to be present. Example usage for the property `createdAt` that may only be set once (during create operation):
```php
$this->property('name')->validator()->string();
$this->property('createdAt')
	->voidable() // property can be missing when allowing voidables in toArray()
	->validator() // validator() must be called after all other property() methods
	->string();
// ...
$entity = new UserEntity(['name' => 'Shay']);
print_r($entity->toArray([], /* allow voidables */ true)); // no assert exception for "createdAt"
// Array ( [name] => Shay )
print_r($entity->toArray()); // voidable not allowed, exception thrown
// Assertion failed: "UserEntity.createdAt" must be a non-empty string (value: [null])
```
The Entity `voidable()` method allows all properties to be missing from the entity, unless a property uses the `notVoidable()` method. Example:
```php
$this->voidable(); // set all properties as voidable (except for "id" below)
$this->property('id')->validator()->string()->notVoidable(); // cannot be missing
$this->property('name')->validator()->string(); // can be missing
$this->property('createdAt')->validator()->string(); // can be missing
$entity = new UserEntity(['id' => 5]);
print_r($entity->toArray([], /* allow voidables */ true)); // no assert exception
```
### Methods
- `assert($name, $value)` - single prop value assertion
- `deregisterProperty($name)` - deregister a property
- `fromArray(array $data)` - properties values setter
- `hasProperty($name): bool` - check if property exists
- `hasVoidableProperty(): bool` - check if any property is voidable
- `isVoidable(): bool` - check if globally voidable
- `property($name, $default = null): \Exo\Entity\Property` - register property
- `toArray(array $filter = null, bool $voidable = false): array` - to array method
	- `$filter` - allows filtering fields
		- remove specific fields: `[field => 0, ...]`
		- include only specific fields: `[field => 1, ...]`
- `validate($name, $value)` - single prop value validation
- `voidable()` - set all properties as voidable (unless properties use `notVoidable()` method)
### Property Methods
- `apply(callable $callback): \Exo\Entity\Property` - apply callback to value
	- note: `$callback(value)` is only called if a value or default value exists for the property
- `bind(\Exo\Entity $entity): \Exo\Entity\Property` - create a reference to another Entity
- `notVoidable(): \Exo\Entity\Property` - cannot be set as voidable
- `validator(): \Exo\Validator` - validator object getter
	- must be called after all other property methods
- `voidable(): \Exo\Entity\Property` - make a property voidable



## `trait Exo\Event`
Event is an event helper.
```php
class User
{
	use \Exo\Event;

	// required
	protected static function &events(): array
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
Exceptions can be improved by using or extending the `Exo\Exception` class, or the other available `Exo\App\Http\Exception\*` exception classes. Example:
```php
use Exo\Exception;
// throw exception with context
throw new Exception('Error message', [
	'id' => 5
]);
```
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

	public static function &classes(): array
	{
		// merge with Exo classes (optional)
		$classes = self::$classes + parent::classes();
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
- `cli(): \Exo\App\Cli`
- `env(): \Exo\App\Env`
- `logger(): \Exo\Logger`
- `map(array $map = null): \Exo\Map`
- `options(array $options = null): \Exo\Options`
- `request(): \Exo\App\Http\Request`
- `response(): \Exo\App\Http\Response`
- `session(): \Exo\App\Http\Request\Session`
- `share(): \Exo\Share`
- `validator(string $name): \Exo\Validator`



## `Exo\Factory\Annotation`
Annotation is a class loading helper that utilizes class annotations.
```php
/**
 * @property \Model\Item $item
 * @property \Model\User $user
 */
class Model extends \Exo\Factory\Annotation {}
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
### Helper Function
Helper function example:
```php
function model(): Model
{
	return Model::getInstance();
}
// usage
$user = model()->user->get($userId);
```



## `Exo\Factory\Dynamic`
Dynamic class factory.
```php
use Exo\Factory\Dynamic as DynamicFactory;
// example instantiate object using dynamic name for Factory\User
$user = (new DynamicFactory('User', 'Factory'))->newInstance($userId);
$user->doSomething(); // example call

// or instantiate object with array of constructor args
$user = (new DynamicFactory('User', 'Factory'))->newInstanceArgs([$userId, $sessId]);

// or static methods
$factory = new DynamicFactory('User', 'Factory');
($factory->getClass())::doSomething(); // example static call

// or use with Singleton (Exo\Factory\Singleton) subclass
$factory = new DynamicFactory('User', 'Factory');
$user = $factory->getInstanceSingleton(); // same as (singleton)::getInstance()
// or call static method
$user = ($factory->getClass())::getInstace();
```
If a class doesn't exist an exception (`Exo\Exception`) with be thrown, use try/catch to handle missing classes:
```php
$factory = new DynamicFactory('User', 'Factory');
try
{
	$user = $factory->newInstance($userId);
}
catch(\Exo\Exception $ex)
{
	logSomething('Factory class does not exist "' . $factory->getClass() . '"');
}
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
	protected static function &classes(): array
	{
		static $classes = [
			'item' => '\Factory\Item',
			'user' => '\Factory\User'
		 ];
		return $classes;
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
class Session extends \Exo\Factory\Singleton {}
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
> This example uses the `Exo\Factory` helper function [`app()`](#exofactory).

```php
use Exo\Logger;
// first setup log handler
// most basic handler, store log records in array:
$logHandler = new \Exo\Logger\ArrayHandler;
Logger::handler($logHandler); // register

// some code
app()->logger('user')->debug('User authenticated', ['id' => $userId]); // channel "user"
// more code
app()->logger('session')->debug('Session started'); // channel "session"
// more code
if($fatal)
{
	app()->logger()->critical('Database connection failed', ['error' => $dbError]); // no channel
}
// get and output log
print_r( $logHandler->close() );
```
Example custom log handler:
```php
class MyLogHandler extends \Exo\Logger\Handler
{
	protected $param;

	public function __construct(string $param, int $level = \Exo\Logger::LEVEL_DEBUG,
		array $channelFilter = null)
	{
		$this->param = $param;
		parent::__construct($level, $channelFilter);
	}

	public function close()
	{
		// do something like output log or close connection
	}

	public function write(\Exo\Logger\Record $record)
	{
		if($this->isHandling($record))
		{
			// do something like write to file or DB table
		}
	}
}
```
The `debug()` method can be used with or without a message, and with or without context. Example:
```php
namespace Test;
class MyClass
{
	public function doAction()
	{
		app()->logger()->debug();
		// message: "\Test\MyClass::doAction"

		app()->logger()->debug(['key' => 'val']);
		// message: "\Test\MyClass::doAction"
		// context: [key => val]
	}
}
```
### Methods
- `critical(?string $message, array $context): \Exo\Logger` - critical log record
- `debug(?string $message, array $context): \Exo\Logger` - debug log record
- `error(?string $message, array $context): \Exo\Logger` - error log record
- `static globalContext(array $context)` - add context to global context
	- Local context will overwrite global context
- `static handler(\Exo\Logger\Handler $handler)` - add log handler
- `info(?string $message, array $context): \Exo\Logger` - info log record
- `warning(?string $message, array $context): \Exo\Logger` - warning log record



## `Exo\Map`
Map is a helper class for handling arrays. Map implements `Countable` and `Iterator`.
### Methods
- `__construct(array $map)` - *overridable*
- `clear($key)` - clear
- `count()` - get count
- `filterKeys(array $filter)` - filter map elements by key (exclude or include)
- `get($key)` - getter
- `has($key)` - check if exists
- `hasValue($value)` - check if value exists
- `isEmpty(): bool` - check it map is empty
- `merge(array $map)` - merge with another map
- `set($key, $value)` - setter
- `toArray(): array` - get as array
### Static Methods
- `arrayFilterKeys(array $array, array $filter): array` - either include or exclude array keys based on the filter
	- `$filter` - allows filtering keys
		- exclude: `[key => 0, ...]`
		- include: `[key => 1, ...]`
- `&extract(array $array, $key, $valueKey = null): array` - extract key or key/value from multidimensional array to one dimensional



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
### Methods
- `entity($data = null): \Exo\Entity` - entity factory method
- `entityArray(array $data, array $filter = null, bool $voidable = false): array` - array of entities factory



## `Exo\Options`
Options is a helper class for handling options with validation.
```php
namespace Canvas;
class RectangleOptions extends \Exo\Options
{
	// valid option keys must be constants that begin with "KEY_"
	const KEY_HEIGHT = 'height';
	const KEY_WIDTH = 'width';

	protected function __construct()
	{
		// all below is optional, set default value to: 300
		$this->option(self::KEY_HEIGHT, 300)
			// validation is optional
			->number();
		$this->option(self::KEY_WIDTH, 600)
	}
	// required, for reading all
	protected function read(array &$map): void
	{
		$map = dbSelectAll('options'); // example: read all from database table
	}
	// required, for writing
	protected function write(string $key, $value): bool
	{
		// example: write to database table
		$data = ['key' => $key, 'value' => $value];
		if($this->has($key))
		{
			// update
			dbUpdate('options', $data);
		}
		else
		{
			// insert
			dbInsert('options', $data);
		}
		return true;
	}
}
class Rectangle
{
	private $h;
	private $w;

	public function __construct()
	{
		$options = RectangleOptions::getInstance(); // singleton
		$this->h = $options->get($options::KEY_HEIGHT);
		$this->w = $options->get($options::KEY_WIDTH);
	}
}
```
Usage example:
```php
use Canvas\Rectangle;
use Canvas\RectangleOptions;

RectangleOptions::getInstance()->set('height', 200);
RectangleOptions::getInstance()->set('width', 400);

$rec = new Rectangle; // h:200, w:400

print_r(RectangleOptions::getInstance()->toArray());
// Array ( [height] => 200 [width] => 400 )
```
### Methods
- `get(string $key): mixed` - value getter
- `has(string $key): bool` - check if key exists
- `option(string $key, $defaultValue): \Exo\Validator` - optional default value setter and validation
- `read(array &$map)` - abstract read all
- `set($key, $value)` - value setter, or use array for keys/values setter
- `toArray(): array` - get options as array `[key => value, ...]`
- `write(string $key, $value): bool` - abstract write key/value



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
$userUsername = 'bob';
$userAge = '';
(new Validator('user.name'))
	->string()
	->assert($userName);
(new Validator('user.username'))
	->string()
	->unique(function($username){
		return true; // should be DB lookup or something
	})
	->assert($userUsername);
(new Validator('user.age'))
	->number()
	->assert($userAge);
// throws exception:
// Assertion failed: "user.age" must be a number (value: "")
```
#### Optional
```php
// all values are considered required unless set as optional:
(new Validator('user.email'))
	->string()
	->email()
	->optional() // not required
	->assert(null); // no exception
// all strings are required unless set as optional:
(new Validator('user.name'))
	->string()
	->assert('');
// throws exception:
//Assertion failed: "user.name" must be a non-empty string (value: "")
```
#### Custom Messages
Use custom validation exception messages:
```php
(new Validator('user.age'))
	->number()->message('Invalid age')
	->between(21, 99)->message('Age must be between 21 and 99')
	->assert('');
// throws exception:
// Assertion failed: "user.age" Invalid age (value: "")
```
Also a singe validation message can be used for an entire group:
```php
(new Validator('user.age'))
	->number()
	->between(21, 99)
	->groupMessage('Invalid age value')
	->assert('');
// throws exception:
// Assertion failed: "user.age" 'Invalid age value (value: "")
```
#### Assert Callback
Use callback with `assert()` method:
```php
(new Validator('age'))
	->number()->message('Invalid age')
	->assert('', function(array $validationMessages){
		handleValidationErrors($validationMessages);
		// return true to halt and not throw validation exception
		return true;
	});
```
#### Custom Rules
Use custom rule:
```php
class MyRule extends \Exo\Validator\Rule
{
	protected $message = 'does not equal "validValue"';
	public function validate($value): bool
	{
		return $value === 'validValue';
	}
}
// usage
validator('myValue')
	->string()
	->rule(new MyRule)
	->assert('badValue');
// throws exception:
// Assertion failed: "myValue" does not equal "validValue" (value: "badValue")
```
#### Validate Method
Usage with `validate()` method instead:
```php
$isValid = (new Validator('user.age'))
	->number()
	->validate($userAge)
if(!$isValid) // do something
```
#### Use Custom Assertion Exception Class
A custom exception class can be used instead of the default `Exo\Validator\Exception` class when an assertion fails, example:
```php
class MyAssertionException extends \Exception {}
// set as exception class for failed assertions:
\Exo\Validator\AbstractType::setAssertionExceptionClass(MyAssertionException::class);
```
#### Display Value in Assertion Exception Message
The value that fails validation can be displayed in the assertion exception message for debugging purposes:
```php
\Exo\Validator\AbstractType::setAssertionExceptionDisplayValue(true);
```
### Types & Rules
- **Array** - must be a non-empty array
	- `depth(int $depth)` - must be an array with specific depth
	- `length(int $length)` - must be a specific number of array items
	- `max(int $max)` - array items must be a maximum of
	- `min(int $min)` - array items must be a minimum of
	- `optional()` - array can be empty
	- `unique()` - array items must be unique
- **Boolean** - must be valid boolean value
	- `optional()` - ignored because `""` and `null` are considered `false` (unless `type()` is used)
	- `type()` - must be primitive type boolean
		- when `type()` is *not* used acceptable boolean values are:
			- for true: `true`, `"1"`, `"true"`, `"on"`, `"yes"`
			- for false: `false`, `"0"`, `"false"`, `"off"`, `"no"`, `""`, `null`
- **Number** - must be a valid number
	- `between(int $min, int $max)` - must be between both values
	- `greaterThan(int $compareValue)` - must be greater than
	- `integer()` - must be an integer
	- `lessThan(int $compareValue)` - must be less than
	- `max(int $max)` - must be a maximum of
	- `min(int $min)` - must be a minimum of
	- `negative()` - must be a negative number
	- `optional()` - allows `""` and `null`
	- `positive()` - must be a positive number
	- `typeFloat()` - must be primitive type float
	- `typeInteger()` - must be primitive type integer
	- `unique(callable $callback)` - must be unique (callable returns `true` if unique)
- **Object** - must be an object
	- `optional()` - allows `null`
- **String** - must be a non-empty string
	- `allowed(array $list)` - must be allowed
	- `alnum(bool $allowWhitespaces = false)` - must only contain alphanumeric characters
	- `alpha(bool $allowWhitespaces = false)` - must only contain alphabetic characters
	- `contains($containsValue, bool $caseSensitive = true)` - must contain value
	- `email()` - must be a valid email address
	- `hash(string $knownHash)` - hashes must be equal
	- `ipv4()` - must be valid IPv4 address
	- `ipv6()` - must be valid IPv6 address
	- `json()` - must be a valid JSON
	- `length(int $length)` - length must be exact number of characters
	- `match(string $compareValue, bool $caseSensitive = true)` - values must be equal
	- `max(int $max)` - length must be a maximum number of characters
	- `min(int $min)` - length must be a minimum number of characters
	- `notAllowed(array $list)` - is not allowed
	- `optional` - allows `""` and `null`
	- `password(string $hash)` - passwords must be equal
	- `regex(string $pattern)` - must match regular expression pattern
	- `type()` - must be primitive type string
	- `unique(callable $callback)` - must be unique (callable returns `true` if unique)
	- `url()` - must be valid URL
### Methods
- `assert($value, callable $callback)` - throws exception if validation fails
- `getMessage()` - get first validation message after validation
- `getMessages(): array` - get all validation messages after validation
- `groupMessage(string $message)` - set group single validation message
- `message(string $message): \Exo\Validator\AbstractType` - set validation message for last rule
- `rule(\Exo\Validator\RuleInterface $rule): \Exo\Validator\AbstractType` - add custom rule
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



## `debug($message = null, $context = null): \Exo\Logger`
Logger debug alias.
```php
debug('Log this', ['context' => 'example']);
```



## `env(string $key, $default)`
The `env()` function is a getter helper function for [Env](#exoappenv).
```php
$dbUser = env('DB_USER');
$dbPassword = env('DB_PWD');
$dbName = env('DB_NAME', 'default'); // default value
```


## `logger(string $channel = ''): \Exo\Logger`
Logger helper function.
```php
logger('channel')->info('Log this', ['context' => 'example']);
```



## `pa(...$values): void`
HTML and CLI friendly printer for all PHP types.
```php
pa(1, ['one'], new stdClass); // print all values
```



## `share(string $key, $value)`
The `share()` function is a getter/setter helper function for [Share](#exoshare).
```php
// setter
share(MY_KEY, 'value');
// getter
$myKey = share(MY_KEY);
```



## `token(int $length = 32): string`
Generate tokens:
```php
$token = token(16);
```