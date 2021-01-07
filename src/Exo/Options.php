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
 * Options class
 *
 * @author Shay Anderson
 */
abstract class Options extends \Exo\Factory\Singleton
{
	/**
	 * Default values
	 *
	 * @var array
	 */
	private $defaultValues = [];

	/**
	 * Options map
	 *
	 * @var array
	 */
	private $map;

	/**
	 * Validators
	 *
	 * @var array (of \Exo\Validators)
	 */
	private $validators = [];

	/**
	 * Value getter
	 *
	 * @param string $key
	 * @return mixed
	 */
	final public function get(string $key)
	{
		if($this->has($key))
		{
			return $this->map[$key];
		}

		if(isset($this->defaultValues[$key]))
		{
			return $this->defaultValues[$key];
		}

		return null;
	}

	/**
	 * Keys getter
	 *
	 * @staticvar array $keys
	 * @return array
	 */
	final public function getKeys(): array
	{
		static $keys;

		if($keys === null)
		{
			$keys = [];
			foreach((new \ReflectionClass($this))->getConstants() as $const => $k)
			{
				if(substr($const, 0, 4) === 'KEY_')
				{
					if(in_array($k, $keys))
					{
						throw new Exception("Options key \"{$k}\" already exists");
					}

					$keys[$const] = $k;
				}
			}
		}

		return $keys;
	}

	/**
	 * Check if key exists
	 *
	 * @param string $key
	 * @return bool
	 */
	final public function has(string $key): bool
	{
		$this->verifyKey($key);
		$this->mapInit();
		return array_key_exists($key, $this->map);
	}

	/**
	 * Init map
	 *
	 * @param bool $forceRead
	 * @return void
	 */
	private function mapInit(bool $forceRead = false): void
	{
		if($this->map === null || $forceRead)
		{
			$this->map = []; // reset
			$this->read($this->map);
		}
	}

	/**
	 * Option object setter
	 *
	 * @param string $key
	 * @param mixed $defaultValue
	 * @return \Exo\Validator
	 * @throws Exception
	 */
	final protected function &option(string $key, $defaultValue = null): \Exo\Validator
	{
		$this->verifyKey($key);

		if(isset($this->validators[$key]))
		{
			throw new Exception('Key already exist as option');
		}

		if(func_num_args() === 2) // default value setter
		{
			$this->defaultValues[$key] = $defaultValue;
		}

		$this->validators[$key] = new Validator($key);
		return $this->validators[$key];
	}

	/**
	 * Read all
	 *
	 * @param array $map
	 * @return void
	 */
	abstract protected function read(array &$map): void;

	/**
	 * Value setter
	 *
	 * @param string|array $key (array: [key => value, ...])
	 * @param mixed $value
	 * @return void
	 */
	final public function set($key, $value = null): void
	{
		if(is_array($key))
		{
			foreach($key as $k => $v)
			{
				$this->set($k, $v);
			}
			return;
		}

		$this->verifyKey($key);

		if(isset($this->validators[$key])
			&& ( $validator = &$this->validators[$key]->typeObject() ) !== null) // validation
		{
			$validator->assert($value);
		}

		if($this->write($key, $value))
		{
			$this->mapInit(true); // force read
		}
	}

	/**
	 * Get all as array
	 *
	 * @return array
	 */
	final public function toArray(): array
	{
		$this->mapInit();

		if(is_array($this->map))
		{
			// merge map and default values
			return $this->map + $this->defaultValues;
		}

		return $this->defaultValues;
	}

	/**
	 * Verify key
	 *
	 * @param string $key
	 * @return void
	 * @throws Exception (invalid key)
	 */
	final private function verifyKey(string $key): void
	{
		if(!in_array($key, $this->getKeys()))
		{
			throw new Exception("Invalid Options key \"{$key}\"");
		}
	}

	/**
	 * Write key/value
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	abstract protected function write(string $key, $value): bool;
}