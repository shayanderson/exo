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
 * Digit rule
 *
 * @author Shay Anderson
 * #docs
 */
class Digit extends \Exo\Validator\Rule
{
	protected $message = 'must be a digit';

	public function validate($value): bool
	{
		if(is_int($value))
		{
			return true;
		}
		else if(is_string($value))
		{
			return ctype_digit($value);
		}

		return false;
	}
}