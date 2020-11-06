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

namespace Exo\Validator\NumberType;

/**
 * Minimum value rule
 *
 * @author Shay Anderson
 */
class Min extends \Exo\Validator\Rule
{
	/**
	 * Max
	 *
	 * @var string
	 */
	protected $message = 'must be a minimum of %s';

	/**
	 * Min
	 *
	 * @var int
	 */
	private $min;

	/**
	 * Init
	 *
	 * @param int $min
	 */
	public function __construct(int $min)
	{
		$this->min = $min;
	}

	/**
	 * Message getter
	 *
	 * @return string
	 */
	public function getMessage(): string
	{
		return sprintf($this->message, $this->min);
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return $value >= $this->min;
	}
}