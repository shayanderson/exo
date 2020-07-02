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
 * Bool rule
 *
 * @author Shay Anderson
 * #docs
 */
class BoolValue extends \Exo\Validator\Rule
{
	protected $message = 'must be bool';

	public function validate($value): bool
	{
		if($value === null || ( is_string($value) && strlen($value) < 1 ))
		{
			return false;
		}

		return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null;
	}
}