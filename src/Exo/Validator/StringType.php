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
 * String validator type
 *
 * @author Shay Anderson
 *
 * @method StringType allowed(array $list) must be allowed
 * @method StringType alnum(bool $allowWhitespaces = false) must only contain alphanumeric characters
 * @method StringType alpha(bool $allowWhitespaces = false) must only contain alphabetic characters
 * @method StringType contains($containsValue, bool $caseSensitive = true) must contain value
 * @method StringType email() must be a valid email address
 * @method StringType hash(string $knownHash) hashes must be equal
 * @method StringType ipv4() must be valid IPv4 address
 * @method StringType ipv6() must be valid IPv6 address
 * @method StringType json() must be a valid JSON
 * @method StringType length(int $length) length must be exact number of characters
 * @method StringType match(string $compareValue, bool $caseSensitive = true) values must be equal
 * @method StringType max(int $max) length must be a maximum number of characters
 * @method StringType min(int $min) length must be a minimum number of characters
 * @method StringType notAllowed(array $list) is not allowed
 * @method StringType password(string $hash) passwords must be equal
 * @method StringType regex(string $pattern) must match regular expression pattern
 * @method StringType type() must be primitive type string
 * @method StringType unique(callable $callback) must be unique
 * @method StringType url() must be valid URL
 */
class StringType extends AbstractType
{
	/**
	 * Base message
	 */
	const BASE_MESSAGE = 'must be a non-empty string';

	/**
	 * Group message setter
	 *
	 * @param string $message
	 * @return StringType
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
	 * @return StringType
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
	 * @return StringType
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
		if($value !== null && !is_scalar($value)) // do not allow non-scalar values, allow null
		{
			return false;
		}

		if(is_bool($value))
		{
			return false;
		}

		return mb_strlen((string)$value) > 0;
	}
}