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

namespace Exo\Validator\StringType;

/**
 * Contains rule
 *
 * @author Shay Anderson
 */
class Contains extends \Exo\Validator\Rule
{
	/**
	 * Contains value
	 *
	 * @var string
	 */
	private $containsValue;

	/**
	 * Case sensitive flag
	 *
	 * @var bool
	 */
	private $isCaseSensitive;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'must contain value "%s"';

	/**
	 * Init
	 *
	 * @param string $containsValue
	 * @param bool $caseSensitive
	 */
	public function __construct(string $containsValue, bool $caseSensitive = true)
	{
		$this->containsValue = $containsValue;
		$this->isCaseSensitive = $caseSensitive;
	}

	/**
	 * Message getter
	 *
	 * @return string
	 */
	public function getMessage(): string
	{
		return sprintf($this->message, $this->containsValue);
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return $this->isCaseSensitive
			? strpos((string)$value, $this->containsValue) !== false
			: stripos((string)$value, $this->containsValue) !== false;
	}
}