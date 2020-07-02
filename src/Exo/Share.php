<?php
/**
 * Exo // Next-Gen Eco Framework
 *
 * @package Exo
 * @copyright 2015-2020 Shay Anderson <https://www.shayanderson.com>
 * @license MIT License <https://github.com/shayanderson/exo/blob/master/LICENSE>
 * @link <https://github.com/shayanderson/exo>
 */
declare(strict_types=1);

namespace Exo;

/**
 * Store
 *
 * @author Shay Anderson
 * #docs
 */
class Share extends Singleton
{
	/**
	 * Map
	 *
	 * @var \Exo\Map
	 */
	private static $map;

	protected function __construct()
	{
		self::$map = new Map;
	}

	public function __get(string $key)
	{
		return $this->get($key);
	}

	public function __set(string $key, $value)
	{
		$this->set($key, $value);
	}

	public function clear(string $key): void
	{
		self::$map->clear($key);
	}

	public function get(string $key)
	{
		return self::$map->get($key);
	}

	public function has(string $key): bool
	{
		return self::$map->has($key);
	}

	public function set(string $key, $value)
	{
		self::$map->set($key, $value);
	}
}