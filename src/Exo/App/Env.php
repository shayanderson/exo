<?php
/**
 * Exo // Next-Gen Eco Framework
 *
 * @package Exo
 * @copyright 2015-2022 Shay Anderson <https://www.shayanderson.com>
 * @license MIT License <https://github.com/shayanderson/exo/blob/master/LICENSE>
 * @link <https://github.com/shayanderson/exo>
 */
declare(strict_types=1);

namespace Exo\App;

use Exo\Exception;
use Exo\Map;

/**
 * Environment variables
 *
 * @author Shay Anderson
 */
class Env extends \Exo\System\Singleton
{
	/**
	 * Map
	 *
	 * @var \Exo\Map
	 */
	private static $map;

	/**
	 * Init
	 */
	protected function __construct()
	{
		self::$map = new Map;

		#next add option to not include $_ENV + $SERVER by default
		foreach($_ENV as $k => $v)
		{
			self::$map->set('ENV.' . $k, filter_var($v, FILTER_SANITIZE_STRING));
		}

		foreach($_SERVER as $k => $v)
		{
			self::$map->set('SERVER.' . $k, filter_var($v, FILTER_SANITIZE_STRING));
		}
	}

	/**
	 * From array setter
	 *
	 * @param array $array
	 * @return void
	 */
	public function fromArray(array $array): void
	{
		self::$map->merge($array);
	}

	/**
	 * Getter
	 *
	 * @param string $key
	 * @param mixed $default
	 * @param bool $invalidKeyException (throw exception on invalid key)
	 * @return mixed
	 * @throws \Exo\Exception (on invalid key)
	 */
	public function get(string $key, $default = null, bool $invalidKeyException = false)
	{
		if(self::$map->has($key))
		{
			return self::$map->get($key);
		}

		if($invalidKeyException)
		{
			throw new Exception('Invalid env variable key "' . $key . '"');
		}

		return $default;
	}

	/**
	 * Check if key exists
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has(string $key): bool
	{
		return self::$map->has($key);
	}

	/**
	 * Load env file
	 *
	 * @param string $path
	 * @return void
	 * @throws \Exo\Exception (on invalid path or load fail)
	 */
	public function load(string $path): void
	{
		if(!is_readable($path))
		{
			throw new Exception('Failed to load env file "' . $path . '"');
		}

		$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		if(!is_array($lines))
		{
			throw new Exception('Failed to load env file');
		}

		$map = [];
		foreach($lines as $k => $v)
		{
			$v = trim($v);

			if($v[0] !== '#' && ( $pos = strpos($v, '=') ) !== false) // no comments
			{
				self::$map->set(substr($v, 0, $pos), trim(substr($v, ++$pos), '"'));
			}
		}

		unset($lines);
	}

	/**
	 * Return all as array
	 *
	 * @param array $filter
	 * @return array
	 */
	public function toArray(array $filter = null): array
	{
		if($filter !== null)
		{
			return Map::arrayFilterKeys(self::$map->toArray(), $filter);
		}

		return self::$map->toArray();
	}
}