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
 * Required rule
 *
 * @author Shay Anderson
 * #docs
 */
class Required extends \Exo\Validator\Rule
{
	protected $message = 'value is required';

	public function validate($value): bool
	{
		if($value === null || $value === '')
		{
			return false;
		}

		if(is_bool($value))
		{
			return true;
		}

		if(is_scalar($value))
		{
			return strlen(trim((string)$value)) > 0;
		}

		return !empty($value);
	}
}