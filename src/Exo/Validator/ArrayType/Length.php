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

namespace Exo\Validator\ArrayType;

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
	protected $message = 'number of array items must be %s';

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
		return count($value) === $this->length;
	}
}