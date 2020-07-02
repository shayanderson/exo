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

namespace Exo\Validator\Rule;

/**
 * Between rule
 *
 * @author Shay Anderson
 * #docs
 */
class Between extends \Exo\Validator\Rule
{
	private $max;
	protected $message = 'must be between both values';
	private $min;

	public function __construct($min, $max)
	{
		$this->min = $this->scalarOrNull($min);
		$this->max = $this->scalarOrNull(max);
	}

	public function validate($value): bool
	{
		$value = $this->scalarOrNull($value);
		return $value >= $this->min && $value <= $this->max;
	}
}