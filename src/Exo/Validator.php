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

use Exo\Validator\AbstractType;
use Exo\Validator\ArrayType;
use Exo\Validator\BooleanType;
use Exo\Validator\NumberType;
use Exo\Validator\ObjectType;
use Exo\Validator\StringType;

/**
 * Validator
 *
 * @author Shay Anderson
 */
class Validator
{
	/**
	 * Name
	 *
	 * @var string
	 */
	private $name = '';

	/**
	 * Type object
	 *
	 * @var \Exo\Validator\AbstractType
	 */
	private $typeObject;

	/**
	 * Init
	 *
	 * @param string $name
	 */
	public function __construct(string $name = null)
	{
		$this->name = $name ?: '';
	}

	/**
	 * Array type
	 *
	 * @return ArrayType
	 */
	public function &arrayType(): ArrayType
	{
		$this->typeObject = new ArrayType($this);
		return $this->typeObject;
	}

	/**
	 * Boolean type
	 *
	 * @return BooleanType
	 */
	public function &boolean(): BooleanType
	{
		$this->typeObject = new BooleanType($this);
		return $this->typeObject;
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
	 * Number type
	 *
	 * @return NumberType
	 */
	public function &number(): NumberType
	{
		$this->typeObject = new NumberType($this);
		return $this->typeObject;
	}

	/**
	 * Object type
	 *
	 * @return ObjectType
	 */
	public function &object(): ObjectType
	{
		$this->typeObject = new ObjectType($this);
		return $this->typeObject;
	}

	/**
	 * Name setter
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * String type
	 *
	 * @return StringType
	 */
	public function &string(): StringType
	{
		$this->typeObject = new StringType($this);
		return $this->typeObject;
	}

	/**
	 * Type object getter
	 *
	 * @return AbstractType|null
	 */
	public function &typeObject(): ?AbstractType
	{
		return $this->typeObject;
	}
}