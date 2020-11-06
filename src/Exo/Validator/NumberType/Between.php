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
 * Between rule
 *
 * @author Shay Anderson
 */
class Between extends \Exo\Validator\Rule
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
	protected $message = 'must be between both values';

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
	 * @param int $max
	 */
	public function __construct(int $min, int $max)
	{
		$this->min = $min;
		$this->max = $max;
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return $value >= $this->min && $value <= $this->max;
	}
}