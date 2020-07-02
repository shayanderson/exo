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
 * Options class
 *
 * @author Shay Anderson
 */
class Options
{
	/**
	 * Map
	 *
	 * @var \Exo\Map
	 */
	private $map;

	/**
	 * Validators
	 *
	 * @var array (of \Exo\Validators)
	 */
	private $validators = [];

	/**
	 * Init
	 *
	 * @param array $options
	 */
	public function __construct(array $options = null)
	{
		$this->__initMap();

		if($options !== null)
		{
			$this->set($options);
		}
	}

	/**
	 * Init map
	 *
	 * @return void
	 */
	private function __initMap(): void
	{
		if(!$this->map) // init
		{
			$this->map = new Map;
		}
	}

	/**
	 * Option value getter
	 *
	 * @param string $key
	 * @return mixed
	 * @throws Exception (key does not exist)
	 */
	final public function get(string $key)
	{
		$this->__initMap();

		if(!$this->map->has($key))
		{
			throw new Exception('Option "' . $key . '" does not exist');
		}

		return $this->map->get($key);
	}

	/**
	 * Validators getter
	 *
	 * @return array
	 */
	final public function getValidators(): array
	{
		return $this->validators;
	}

	/**
	 * Check if key exists
	 *
	 * @param string $key
	 * @return bool
	 */
	final public function has(string $key): bool
	{
		$this->__initMap();
		return $this->map->has($key);
	}

	/**
	 * Merge options with validators
	 *
	 * @param \Exo\Options $options (overrides internal options)
	 * @return void
	 */
	final public function merge(\Exo\Options $options): void
	{
		$this->__initMap();
		$this->map->merge($options->toArray());
		$this->validators = array_merge($this->validators, $options->getValidators());
	}

	/**
	 * Set option validator
	 *
	 * @param string $key
	 * @return \Exo\Validator
	 * @throws Exception (option already exists)
	 */
	final public function &option(string $key): \Exo\Validator
	{
		$this->__initMap();
		if($this->map->has($key))
		{
			throw new Exception('Option "' . $key . '" already exists before using '
				. __METHOD__ . '()');
		}

		if(isset($this->validators[$key]))
		{
			return $this->validators[$key];
		}

		$this->validators[$key] = new Validator($key);

		return $this->validators[$key];
	}

	/**
	 * Set option keys as required
	 *
	 * @param array $keys
	 * @return void
	 */
	final public function required(array $keys): void
	{
		foreach($keys as $k)
		{
			$this->option($k)->required();
		}
	}

	/**
	 * Single or multiple options with values setter
	 *
	 * @param string|array $key (array ex: [key => value, ...])
	 * @param mixed $value
	 * @return void
	 * @throws Exception (validation fail)
	 */
	final public function set($key, $value = null): void
	{
		$this->__initMap();

		if(is_array($key))
		{
			foreach($key as $k => $v)
			{
				$this->set($k, $v);
			}
			return;
		}

		if(!is_string($key))
		{
			throw new Exception('Invalid key "' . $key . '" (must be string)');
		}

		$this->validatorAssert($key, $value);
		$this->map->set($key, $value);
	}

	/**
	 * Options as array getter
	 *
	 * @return array
	 * @throws Exception (validation fail)
	 */
	final public function toArray(): array
	{
		$this->__initMap();

		foreach($this->map as $k => $v)
		{
			$this->validatorAssert($k, $v);
		}

		return $this->map->toArray();
	}

	/**
	 * Validator assert
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	final private function validatorAssert(string $key, $value): void
	{
		if(isset($this->validators[$key]))
		{
			$this->validators[$key]->assert($value);
		}
	}
}