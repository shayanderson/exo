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

namespace Exo\Validator;

/**
 * Array validator type
 *
 * @author Shay Anderson
 *
 * @method ArrayType depth(int $depth) must be an array with specific depth
 * @method ArrayType length(int $length) must be a specific number of array items
 * @method ArrayType max(int $max) array items must be a maximum of
 * @method ArrayType min(int $min) array items must be a minimum of
 * @method ArrayType unique() array items must be unique
 */
class ArrayType extends AbstractType
{
	/**
	 * Base message
	 */
	const BASE_MESSAGE = 'must be an array';

	/**
	 * Group message setter
	 *
	 * @param string $message
	 * @return ArrayType
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
	 * @return ArrayType
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
		return $value === [];
	}

	/**
	 * Set as optional
	 *
	 * @return ArrayType
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
		return is_array($value) && count($value);
	}
}