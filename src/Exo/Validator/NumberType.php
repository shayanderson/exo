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

namespace Exo\Validator;

/**
 * Number validator type
 *
 * @author Shay Anderson
 *
 * @method NumberType between(int $min, int $max) must be between both values
 * @method NumberType greaterThan(int $compareValue) must be greater than
 * @method NumberType integer() must be an integer
 * @method NumberType lessThan(int $compareValue) must be less than
 * @method NumberType max(int $max) must be a maximum of
 * @method NumberType min(int $min) must be a minimum of
 * @method NumberType negative() must be a negative number
 * @method NumberType positive() must be a positive number
 * @method NumberType typeFloat() must be primitive type float
 * @method NumberType typeInteger() must be primitive type integer
 * @method StringType unique(callable $callback) must be unique
 */
class NumberType extends AbstractType
{
	/**
	 * Base message
	 */
	const BASE_MESSAGE = 'must be a number';

	/**
	 * Group message setter
	 *
	 * @param string $message
	 * @return NumberType
	 */
	public function groupMessage(string $message): self
	{
		parent::groupMessage($message);
		return $this;
	}

	/**
	 * Message setter
	 *
	 * @param string $message
	 * @return NumberType
	 */
	public function message(string $message): self
	{
		parent::message($message);
		return $this;
	}

	/**
	 * Check if optional match
	 *
	 * @param mixed $value
	 * @return bool
	 */
	protected static function isOptionalMatch($value): bool
	{
		return $value === null || $value === '';
	}

	/**
	 * Set as optional
	 *
	 * @return NumberType
	 */
	public function optional(): self
	{
		parent::optional();
		return $this;
	}

	/**
	 * Validate base
	 *
	 * @param mixed $value
	 * @return bool
	 */
	protected static function validateBase($value): bool
	{
		if(!is_numeric($value))
		{
			return false;
		}

		return !is_nan((float)$value);
	}
}