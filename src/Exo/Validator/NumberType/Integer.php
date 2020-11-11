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
 * Integer rule
 *
 * @author Shay Anderson
 */
class Integer extends \Exo\Validator\Rule
{
	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'must be an integer';

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return is_int($value) || ctype_digit($value);
	}
}