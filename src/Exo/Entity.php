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

use Exo\Entity\Property;

/**
 * Model entity
 *
 * @author Shay Anderson
 */
abstract class Entity
{
	/**
	 * Class properties
	 *
	 * @var array
	 */
	private $__classProps__;

	/**
	 * Voidable flag
	 *
	 * @var bool
	 */
	private $__isVoidable__ = false;

	/**
	 * Properties
	 *
	 * @var \Exo\Map
	 */
	private $__props__;

	/**
	 * Init
	 *
	 * @param array $data
	 */
	public function __construct(array $data = null)
	{
		$this->__props__ = [];
		$this->__classProps__ = get_object_vars($this);
		unset(
			$this->__classProps__['__classProps__'],
			$this->__classProps__['__isVoidable__'],
			$this->__classProps__['__props__']
		);
		foreach($this->__classProps__ as $k => $v)
		{
			$this->__classProps__[$k] = true;
		}
		$this->register();

		if($data)
		{
			$this->fromArray($data);
		}
	}

	/**
	 * Getter
	 *
	 * @param mixed $name
	 * @return mixed
	 */
	final public function __get($name)
	{
		return $this->getProp($name)->getValue();
	}

	/**
	 * Init (ensure self constructor has been called)
	 *
	 * @return void
	 * @throws \Exo\Exception
	 */
	private function __init(): void
	{
		if($this->__props__ === null)
		{
			throw new Exception('Entity parent::__construct() must be called in'
				. ' ' . static::class . ' before calling ' . static::class . '::register()');
		}
	}

	/**
	 * Value setter
	 *
	 * @param mixed $name
	 * @param mixed $value
	 * @return void
	 */
	final public function __set($name, $value): void
	{
		$this->getProp($name)->setValue($value);
	}

	/**
	 * Single property value assertion
	 *
	 * @param mixed $name
	 * @param mixed $value
	 * @return void
	 */
	final public function assert($name, $value): void
	{
		$validator = &$this->getProp($name)->getValidator();

		if($validator)
		{
			(clone $validator->typeObject())->assert($value);
		}
	}

	/**
	 * Deregister a property
	 *
	 * @param mixed $name
	 * @return void
	 */
	final public function deregisterProperty($name): void
	{
		$this->verifyPropExists($name);
		unset($this->__props__[$name]);
	}

	/**
	 * Value setter from array
	 *
	 * @param array $data
	 * @return void
	 */
	final public function fromArray(array $data): void
	{
		foreach($data as $k => $v)
		{
			$this->__set($k, $v);
		}
	}

	/**
	 * Prop object getter
	 *
	 * @param mixed $name
	 * @return \Exo\Entity\Property
	 */
	private function &getProp($name): Property
	{
		$this->verifyPropExists($name);
		return $this->__props__[$name];
	}

	/**
	 * Check if property exists
	 *
	 * @param mixed $name
	 * @return bool
	 */
	final public function hasProperty($name): bool
	{
		$this->__init();
		return isset($this->__props__[$name]);
	}

	/**
	 * Check if voidable property exists
	 *
	 * @return bool
	 */
	final public function hasVoidableProperty(): bool
	{
		$this->__init();
		foreach($this->__props__ as $prop)
		{
			if($prop->isVoidable())
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if globally voidable
	 *
	 * @return bool
	 */
	final public function isVoidable(): bool
	{
		return $this->__isVoidable__;
	}

	/**
	 * Property setter
	 *
	 * @param mixed $name
	 * @param mixed $default
	 * @return \Exo\Entity\Property
	 * @throws \Exo\Exception (on class property exists)
	 */
	final protected function &property($name, $default = null): Property
	{
		$this->__init();

		if(isset($this->__classProps__[$name]))
		{
			throw new Exception('Class property "' . $name . '" cannot exist in Entity'
				. ' class ' . static::class . ' and also be set by ' . static::class
				. '::property("' . $name . '")');
		}

		$this->__props__[$name] = new Property($name, static::class);

		if(func_num_args() > 1)
		{
			$this->__props__[$name]->default($default);
		}

		return $this->__props__[$name];
	}

	/**
	 * Register properties
	 */
	abstract protected function register(): void;

	/**
	 * Property key/values as array getter
	 *
	 * @param array $filter (ex: include only: [key => 1], exclude only: [key => 0])
	 * @param bool $voidable (allow voidable props to be missing)
	 * @return array
	 */
	final public function &toArray(array $filter = null, bool $voidable = false): array
	{
		$this->__init();

		$map = [];
		$props = $this->__props__;

		if($filter)
		{
			$props = Map::arrayFilterKeys($props, $filter);
		}

		/* @var $prop \Exo\Entity\Property */
		foreach($props as $prop)
		{
			// property is voidable + is void
			if($voidable && $prop->isVoid() && !$prop->isNotVoidable())
			{
				continue;
			}

			// global voidable + is void
			if($voidable && $this->__isVoidable__ && !$prop->hasValue() && !$prop->isNotVoidable())
			{
				continue;
			}

			$map[$prop->getName()] = $prop->getValue($voidable);

			if($prop->hasRef())
			{
				// override for ref assertion
				$map[$prop->getName()] = $prop->getRef()->toArray(null, $voidable);
			}
		}

		return $map;
	}

	/**
	 * Single property value validation
	 *
	 * @param mixed $name
	 * @param mixed $value
	 * @return bool
	 */
	final public function validate($name, $value): bool
	{
		$validator = &$this->getProp($name)->getValidator();

		if($validator)
		{
			return (clone $validator->typeObject())->validate($value);
		}

		return true;
	}

	/**
	 * Verify prop exists
	 *
	 * @param mixed $name
	 * @return void
	 * @throws \Exo\Exception (on prop does not exist)
	 */
	final private function verifyPropExists($name): void
	{
		$this->__init();

		if(!isset($this->__props__[$name]))
		{
			throw new Exception('Property "' . $name . '" does not exist in Entity class'
				. ' ' . static::class);
		}
	}

	/**
	 * Allow all properties voidable
	 *
	 * @return void
	 */
	final protected function voidable(): void
	{
		$this->__isVoidable__ = true;
	}
}