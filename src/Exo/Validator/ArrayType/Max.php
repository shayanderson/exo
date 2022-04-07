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

namespace Exo\Validator\ArrayType;

/**
 * Maximum rule
 *
 * @author Shay Anderson
 */
class Max extends \Exo\Validator\Rule
{
	/**
	 * Max
	 *
	 * @var int
	 */
	private $max;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'array items must be a maximum of %s';

	/**
	 * Init
	 *
	 * @param int $max
	 */
	public function __construct(int $max)
	{
		$this->max = $max;
	}

	/**
	 * Message getter
	 *
	 * @return string
	 */
	public function getMessage(): string
	{
		return sprintf($this->message, $this->max);
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		if(!count($value))
		{
			return true;
		}

		return max($value) <= $this->max;
	}
}