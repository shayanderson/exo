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
 * Map
 *
 * @author Shay Anderson
 */
class Map implements \Countable, \Iterator
{
	/**
	 * Map
	 *
	 * @var array
	 */
	private $map = [];

	/**
	 * Init
	 *
	 * @param array $map
	 */
	public function __construct(array $map = null)
	{
		if($map)
		{
			$this->map = $map;
		}
	}

	/**
	 * Value getter
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get(string $key)
	{
		return $this->get($key);
	}

	/**
	 * Value setter
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function __set(string $key, $value): void
	{
		$this->set($key, $value);
	}

	/**
	 * Filters keys based on include or exclude filter
	 *
	 * @param array $array
	 * @param array $filter (include: [key => 1, ...] or exclude: [key => 0, ...])
	 * @return array
	 */
	final public static function arrayFilterKeys(array $array, array $filter): array
	{
		$isExclude = reset($filter) == 0;

		foreach($array as $k => $v)
		{
			if($isExclude)
			{
				if(isset($filter[$k]) && $filter[$k] == 0)
				{
					unset($array[$k]);
				}
			}
			else // include
			{
				if(isset($filter[$k]) && $filter[$k] == 1)
				{
					continue;
				}

				unset($array[$k]);
			}
		}

		return $array;
	}

	/**
	 * Clear
	 *
	 * @param mixed $key
	 * @return void
	 */
	public function clear($key): void
	{
		if($this->has($key))
		{
			unset($this->map[$key]);
		}
	}

	/**
	 * Count
	 *
	 * @return int
	 */
	final public function count(): int
	{
		return count($this->map);
	}

	/**
	 * Current
	 *
	 * @return mixed
	 */
	final public function current()
	{
		return current($this->map);
	}

	/**
	 * Filter keys
	 *
	 * @param array $filter (include: [key => 1, ...] or exclude: [key => 0, ...])
	 * @return void
	 */
	public function filterKeys(array $filter): void
	{
		$this->map = self::arrayFilterKeys($this->map, $filter);
	}

	/**
	 * Getter
	 *
	 * @param mixed $key
	 * @return mixed (null if key does not exist)
	 */
	public function get($key)
	{
		return $this->has($key) ? $this->map[$key] : null;
	}

	/**
	 * Check if key exists
	 *
	 * @param mixed $key
	 * @return bool
	 */
	public function has($key): bool
	{
		return isset($this->map[$key]) || array_key_exists($key, $this->map);
	}

	/**
	 * Check if value exists
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function hasValue($value): bool
	{
		return array_search($value, $this->map) !== false;
	}

	/**
	 * Check if empty
	 *
	 * @return bool
	 */
	public function isEmpty(): bool
	{
		return !( $this->count() > 0 );
	}

	/**
	 * Fetch key
	 *
	 * @return mixed
	 */
	final public function key()
	{
		return key($this->map);
	}

	/**
	 * Merge maps
	 *
	 * @param array $map (overrides internal map)
	 * @return void
	 */
	public function merge(array $map): void
	{
		$this->map = array_merge($this->map, $map);
	}

	/**
	 * Advance pointer
	 *
	 * @return void
	 */
	final public function next(): void
	{
		next($this->map);
	}

	/**
	 * Reset pointer
	 *
	 * @return void
	 */
	final public function rewind(): void
	{
		reset($this->map);
	}

	/**
	 * Setter
	 *
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function set($key, $value): void
	{
		$this->map[$key] = $value;
	}

	/**
	 * Get as array
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		return $this->map;
	}

	/**
	 * Validate current position
	 *
	 * @return bool
	 */
	final public function valid(): bool
	{
		return key($this->map) !== null;
	}
}