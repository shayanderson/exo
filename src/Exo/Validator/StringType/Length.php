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

namespace Exo\Validator\StringType;

/**
 * Length rule
 *
 * @author Shay Anderson
 */
class Length extends \Exo\Validator\Rule
{
	/**
	 * Length
	 *
	 * @var int
	 */
	private $length;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'length must be %s characters';

	/**
	 * Init
	 *
	 * @param int $length
	 */
	public function __construct(int $length)
	{
		$this->length = $length;
	}

	/**
	 * Message getter
	 *
	 * @return string
	 */
	public function getMessage(): string
	{
		return sprintf($this->message, $this->length);
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return mb_strlen((string)$value) === $this->length;
	}
}