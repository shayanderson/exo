<?php
/**
 * Exo // Next-Gen Eco Framework
 *
 * @package Exo
 * @copyright 2015-2022 Shay Anderson <https://www.shayanderson.com>
 * @license MIT License <https://github.com/shayanderson/exo/blob/master/LICENSE>
 * @link <https://github.com/shayanderson/exo>
 */
declare(strict_types=1);

namespace Exo\Validator\StringType;

/**
 * Match rule
 *
 * @author Shay Anderson
 */
class Match extends \Exo\Validator\Rule
{
	/**
	 * Case sensitive flag
	 *
	 * @var bool
	 */
	private $caseSensitive = true;

	/**
	 * Compare value
	 *
	 * @var string
	 */
	private $compareValue;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'value must be equal to "%s"';

	/**
	 * Init
	 *
	 * @param string $compareValue
	 * @param bool $caseSensitive
	 */
	public function __construct(string $compareValue, bool $caseSensitive = true)
	{
		$this->compareValue = $compareValue;
		$this->caseSensitive = $caseSensitive;
	}

	/**
	 * Message getter
	 *
	 * @return string
	 */
	public function getMessage(): string
	{
		return sprintf($this->message, $this->compareValue);
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return ($this->caseSensitive ? 'strcmp' : 'strcasecmp')
			((string)$value, $this->compareValue) === 0;
	}
}