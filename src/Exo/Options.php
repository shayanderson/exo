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
	 * Options map
	 *
	 * @var array
	 */
	private $map;

	/**
	 * Option objects
	 *
	 * @var array
	 */
	private $mapOption = [];

	/**
	 * Convert value type
	 *
	 * @param string $type
	 * @param mixed $value
	 * @return void
	 * @throws Exception (invalid type)
	 */
	private static function convertType(string $type, &$value): void
	{
		switch($type)
		{
			case 'auto':
				if(is_string($value))
				{
					if($value === 'true') // auto convert bool
					{
						$value = true;
					}
					else if($value === 'false') // auto convert bool
					{
						$value = false;
					}
					else if($value === 'null') // auto convert null
					{
						$value = null;
					}
					else if(ctype_digit($value)) // auto convert int
					{
						$value = (int)$value;
					}
					else if(is_numeric($value)) // auto convert float
					{
						$value = (float)$value;
					}
				}
				break;

			case 'bool':
			case 'boolean':
				if(!is_bool($value))
				{
					if($value === 'true')
					{
						$value = true;
					}
					else if($value === 'false')
					{
						$value = false;
					}
					else
					{
						$value = (bool)$value;
					}
				}
				break;

			case 'int':
			case 'integer':
				$value = (int)$value;
				break;

			case 'none':
				// do nothing
				break;

			case 'number':
				if(ctype_digit($value))
				{
					$value = (int)$value;
				}
				else if(is_numeric($value))
				{
					$value = (float)$value;
				}
				else
				{
					$value = (int)$value;
				}
				break;

			case 'string':
				$value = (string)$value;
				break;

			default:
				throw new Exception("Unknown type \"{$type}\" when trying to convert type");
				break;
		}
	}

	/**
	 * Set key values from array
	 *
	 * @param array $array
	 * @return void
	 */
	final public function fromArray(array $array): void
	{
		foreach($array as $k => $v)
		{
			$this->set($k, $v);
		}
	}

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

		if(isset($this->mapOption[$key]))
		{
			return $this->mapOption[$key]->toArray()['default'];
		}

		return null;
	}

	/**
	 * Keys getter
	 *
	 * @staticvar array $keys
	 * @return array
	 */
	final protected function getKeys(): array
	{
		static $keys;

		if($keys === null)
		{
			$keys = [];
			foreach((new \ReflectionClass($this))->getConstants() as $const => $k)
			{
				if(substr($const, 0, 4) === 'KEY_')
				{
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
	 * @return \Exo\Options\Option
	 */
	final protected function &option(string $key): \Exo\Options\Option
	{
		$this->verifyKey($key);

		if(isset($this->mapOption[$key]))
		{
			throw new Exception('Key already exist as option (' . __METHOD__ . ')');
		}

		$this->mapOption[$key] = new \Exo\Options\Option($key);
		return $this->mapOption[$key];
	}

	/**
	 * Read all
	 *
	 * @return void
	 */
	abstract protected function read(array &$map): void;

	/**
	 * Value setter
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	final public function set(string $key, $value): void
	{
		$this->verifyKey($key);

		// convert type?
		$type = isset($this->mapOption[$key])
			? $this->mapOption[$key]->toArray()['type']
			: 'auto'; // auto by default
		self::convertType($type, $value);

		if(isset($this->mapOption[$key])
			&& ( $validator = $this->mapOption[$key]->toArray()['validator'] ))
		{
			/* @var $validator \Exo\Validator */
			$validator->typeObject()->assert($value);
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
		return $this->map ?: [];
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
	 * @return void
	 */
	abstract protected function write(string $key, $value): bool;
}