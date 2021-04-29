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

namespace Exo\Entity;

use Exo\Entity;
use Exo\Exception;
use Exo\Validator;

/**
 * Entity property
 *
 * @author Shay Anderson
 */
class Property
{
	/**
	 * Callback
	 *
	 * @var callable
	 */
	private $callback;

	/**
	 * Class name
	 *
	 * @var string
	 */
	private $class;

	/**
	 * Default value
	 *
	 * @var mixed
	 */
	private $defaultValue;

	/**
	 * Default value has been set flag
	 *
	 * @var bool
	 */
	private $isDefaultValue = false;

	/**
	 * Is not voidable flag
	 *
	 * @var bool
	 */
	private $isNotVoidable = false;

	/**
	 * Value has been set flag
	 *
	 * @var bool
	 */
	private $isValue = false;

	/**
	 * Voidable flag
	 *
	 * @var bool
	 */
	private $isVoidable = false;

	/**
	 * Name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Entity reference
	 *
	 * @var \Exo\Entity
	 */
	private $ref;

	/**
	 * Validator
	 *
	 * @var \Exo\Validator
	 */
	private $validator;

	/**
	 * Value
	 *
	 * @var mixed
	 */
	private $value;

	/**
	 * Init
	 *
	 * @param string $name
	 * @param string $class
	 */
	public function __construct(string $name, string $class)
	{
		$this->name = $name;
		$this->class = str_replace('\\', '.', $class);
	}

	/**
	 * Apply callback to value
	 *
	 * @param callable $callback
	 * @return self
	 */
	public function apply(callable $callback): self
	{
		$this->callback = $callback;
		return $this;
	}

	/**
	 * Trigger validator assert
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	private function &assert($value)
	{
		if($this->validator)
		{
			$this->validator->typeObject()->assert($value);
		}

		return $value;
	}

	/**
	 * Bind entity reference
	 *
	 * @param Entity $entity
	 * @return self
	 */
	public function bind(Entity $entity): self
	{
		$this->ref = &$entity;
		return $this;
	}

	/**
	 * Default value setter
	 *
	 * @param mixed $value
	 * @return self
	 */
	public function default($value): self
	{
		$this->defaultValue = $value;
		$this->isDefaultValue = true;
		return $this;
	}

	/**
	 * Internal value getter
	 *
	 * @return mixed
	 */
	private function get()
	{
		if($this->isValue)
		{
			if($this->callback)
			{
				return $this->assert(
					($this->callback)($this->value)
				);
			}

			return $this->value;
		}

		if($this->callback && $this->isDefaultValue)
		{
			return $this->assert(
				($this->callback)($this->value)
			);
		}

		return $this->assert($this->defaultValue);
	}

	/**
	 * Name getter
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Entity reference getter
	 *
	 * @return \Exo\Entity
	 */
	public function &getRef(): Entity
	{
		return $this->ref;
	}

	/**
	 * Validator getter
	 *
	 * @return Validator|null
	 */
	public function &getValidator(): ?\Exo\Validator
	{
		return $this->validator;
	}

	/**
	 * Value getter
	 *
	 * @return mixed
	 */
	public function getValue(bool $voidable = false)
	{
		if($this->ref)
		{
			$this->get(); // self assertion first
			return $this->ref->toArray(null, $voidable);
		}

		return $this->get();
	}

	/**
	 * Check if entity reference exists
	 *
	 * @return bool
	 */
	public function hasRef(): bool
	{
		return $this->ref !== null;
	}

	/**
	 * Check if value exists
	 *
	 * @return bool
	 */
	public function hasValue(): bool
	{
		return $this->isValue;
	}

	/**
	 * Check if voidable is not allowed
	 *
	 * @return bool
	 */
	public function isNotVoidable(): bool
	{
		return $this->isNotVoidable;
	}

	/**
	 * Check if is voidable and if value does not exist
	 *
	 * @return bool
	 */
	public function isVoid(): bool
	{
		if(!$this->isVoidable())
		{
			return false;
		}

		return !$this->isValue;
	}

	/**
	 * Voidable flag getter
	 *
	 * @return bool
	 */
	public function isVoidable(): bool
	{
		return $this->isVoidable;
	}

	/**
	 * Set as not voidable
	 *
	 * @return self
	 */
	public function notVoidable(): self
	{
		if($this->isVoidable)
		{
			throw new Exception(sprintf(
				'Property "%s.%s" cannot be set as not voidable when already set as voidable',
				$this->class,
				$this->name
			));
		}

		$this->isNotVoidable = true;
		return $this;
	}

	/**
	 * Value setter
	 *
	 * @param mixed $value
	 * @return void
	 */
	public function setValue($value): void
	{
		if($this->ref)
		{
			$this->ref->fromArray((array)$value);
		}

		// set for self assertion
		$this->value = &$this->assert($value);
		$this->isValue = true;
	}

	/**
	 * Validator object setter/getter
	 *
	 * @return \Exo\Validator
	 */
	public function &validator(): Validator
	{
		$this->validator = new Validator($this->class . '.' . $this->name);
		return $this->validator;
	}

	/**
	 * Set as voidable
	 *
	 * @return self
	 */
	public function voidable(): self
	{
		if($this->isNotVoidable)
		{
			throw new Exception(sprintf(
				'Property "%s.%s" cannot be voidable when already set as not voidable',
				$this->class,
				$this->name
			));
		}

		$this->isVoidable = true;
		return $this;
	}
}