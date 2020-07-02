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
 * Length rule
 *
 * @author Shay Anderson
 * #docs
 */
class Length extends \Exo\Validator\Rule
{
	private $exact;
	private $min;
	protected $message = 'must be a specific length';
	private $max;

	public function __construct(?int $min = null, ?int $max = null, ?int $exact = null)
	{
		$this->min = (int)$min;
		$this->max = (int)$max;
		$this->exact = (int)$exact;
	}

	public function validate($value): bool
	{
		$value = (string)$this->scalarOrNull($value);

		if($this->min && $this->max)
		{
			return strlen($value) >= $this->min && strlen($value) <= $this->max;
		}
		else if($this->min)
		{
			return strlen($value) >= $this->min;
		}
		else if($this->max)
		{
			return strlen($value) <= $this->max;
		}
		if($this->exact)
		{
			return strlen($value) === $this->exact;
		}

		return false;
	}
}