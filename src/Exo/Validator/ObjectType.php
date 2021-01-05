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
 * Object validator type
 *
 * @author Shay Anderson
 */
class ObjectType extends AbstractType
{
	/**
	 * Base message
	 */
	const BASE_MESSAGE = 'must be an object';

	/**
	 * Group message setter
	 *
	 * @param string $message
	 * @return ObjectType
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
	 * @return ObjectType
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
		return $value === null;
	}

	/**
	 * Set as optional
	 *
	 * @return ObjectType
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
		return is_object($value);
	}
}