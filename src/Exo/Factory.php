<?php
/**
 * Exo // Next-Gen Eco Framework
 *
 * @package Exo
 * @copyright 2015-2021 Shay Anderson <https://www.shayanderson.com>
 * @license MIT License <https://github.com/shayanderson/exo/blob/master/LICENSE>
 * @link <https://github.com/shayanderson/exo>
 */
declare(strict_types=1);

namespace Exo;

/**
 * Factory
 *
 * @author Shay Anderson
 *
 * @method \Exo\App\Cli cli()
 * @method \Exo\App\Env env()
 * @method \Exo\Logger logger()
 * @method \Exo\App\Http\Request request()
 * @method \Exo\App\Http\Response response()
 * @method \Exo\App\Http\Request\Session session()
 * @method \Exo\Share share()
 * @method \Exo\Validator validator(string $name)
 */
class Factory extends Factory\Mapper
{
	/**
	 * Class map
	 *
	 * @var array
	 */
	private static $classes = [
		'cli' => App\Cli::class,
		'env' => App\Env::class,
		'logger' => Logger::class,
		'request' => App\Http\Request::class,
		'response' => App\Http\Response::class,
		'session' => App\Http\Request\Session::class,
		'share' => Share::class,
		'validator' => Validator::class
	];

	/**
	 * Instances
	 *
	 * @var array
	 */
	private static $instances = [];

	/**
	 * Call
	 *
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 */
	final public function __call(string $name, array $args)
	{
		if($name === 'cli' && !\Exo\App\Cli::isCli()) // do not allow CLI object access if not CLI
		{
			return;
		}

		// do not log logger (redundant) + its used in System::debug() (infinite loop)
		if(System::isDebugging() && $name !== 'logger')
		{
			System::debug(__METHOD__, [
				'method' => $name,
				'class' => self::$classes[$name] ?? null,
				'args' => $args
			]);
		}

		return parent::__call($name, $args);
	}

	/**
	 * Classes getter
	 *
	 * @return array
	 */
	protected static function &classes(): array
	{
		return self::$classes;
	}

	/**
	 * Instances getter
	 *
	 * @return array
	 */
	protected static function &instances(): array
	{
		return self::$instances;
	}
}