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

namespace Exo\Validator\NumberType;

/**
 * Less than rule
 *
 * @author Shay Anderson
 */
class LessThan extends \Exo\Validator\Rule
{
	/**
	 * Compare value
	 *
	 * @var int
	 */
	private $compareValue;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'must be less than %s';

	/**
	 * Init
	 *
	 * @param int $compareValue
	 */
	public function __construct(int $compareValue)
	{
		$this->compareValue = $compareValue;
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
		return $value < $this->compareValue;
	}
}