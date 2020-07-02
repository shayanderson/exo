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
 * Model entity
 *
 * @author Shay Anderson
 * #docs
 */
abstract class Entity
{
	/**
	 * Properties
	 *
	 * @var \Exo\Map
	 */
	private $__map;

	private $__validators = [];

	public function __construct(array $data = null)
	{
		$this->__map = new \Exo\Map;

		foreach($this->getProps() as $k => $v)
		{
			if($v instanceof Validator) // prop => validator
			{
				$v->setName($k); // set validator name
				$this->__validators[$k] = $v;
				$this->__map->set($k, null);
			}
			else // prop only
			{
				$this->__map->set($v, null);
			}
		}

		$props = get_object_vars($this);
		unset($props['__map'], $props['__validators']);
		foreach($props as $prop => $v)
		{
			if($this->__map->has($prop))
			{
				throw new Exception('Class property "' . $prop . '" cannot exist in Entity'
					. ' class "' . static::class . '" and also be returned by ' . static::class
					. '::getProps()');
			}
		}

		if($data) // setter
		{
			foreach($data as $k => $v)
			{
				$this->__set($k, $v);
			}
		}
	}

	final public function __get(string $property)
	{
		$this->verifyPropExists($property);
		$this->assert($property, $this->__map->get($property));

		return $this->__map->get($property);
	}

	final public function __set(string $property, $value): void
	{
		$this->verifyPropExists($property);
		$this->assert($property, $value);

		$this->__map->set($property, $value);
	}

	final private function assert(string $property, $value): void
	{
		if(isset($this->__validators[$property]))
		{
			$this->__validators[$property]->assert($value);
		}
	}

	final public function fromArray(array $props): void
	{
		foreach($props as $k => $v)
		{
			$this->__set($k, $v);
		}
	}

	abstract protected function getProps(): array;

	final public function &toArray(array $filter = null): array
	{
		$arr = $this->__map->toArray();

		if($filter)
		{
			$rm = in_array(0, $filter);

			foreach($arr as $k => $v)
			{
				if($rm) // remove
				{
					if(isset($filter[$k]) && $filter[$k] === 0)
					{
						unset($arr[$k]);
					}
				}
				else // include
				{
					if(isset($filter[$k]) && $filter[$k] === 1)
					{
						continue;
					}

					unset($arr[$k]);
				}
			}
		}

		foreach($arr as $k => $v)
		{
			$this->assert($k, $v);
		}

		return $arr;
	}

	final public function validator(): \Exo\Validator
	{
		return new Validator;
	}

	final private function verifyInit(): void
	{
		if(!$this->__map)
		{
			throw new Exception('Entity parent::__construct() must be called in'
				. ' "' . static::class . '", before property getters or setters are used');
		}
	}

	final private function verifyPropExists(string $property): void
	{
		$this->verifyInit();

		if(!$this->__map->has($property))
		{
			throw new Exception('Property "' . $property . '" does not exist in Entity class'
				. ' "' . static::class . '"');
		}
	}
}