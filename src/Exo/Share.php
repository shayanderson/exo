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
 * Share
 *
 * @author Shay Anderson
 */
class Share extends System\Singleton
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
	}

	/**
	 * Getter
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get(string $key)
	{
		return $this->get($key);
	}

	/**
	 * Setter
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set(string $key, $value)
	{
		$this->set($key, $value);
	}

	/**
	 * Clear
	 *
	 * @param string $key
	 * @return void
	 */
	public function clear(string $key): void
	{
		self::$map->clear($key);
	}

	/**
	 * Getter
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get(string $key)
	{
		return self::$map->get($key);
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
	 * Setter
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function set(string $key, $value)
	{
		self::$map->set($key, $value);
	}

	/**
	 * To array
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		return self::$map->toArray();
	}
}