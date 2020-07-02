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
 * JSON rule
 *
 * @author Shay Anderson
 * #docs
 */
class Json
{
	protected $message = 'must be a valid JSON';

	public function validate($value): bool
	{
		if(is_string($value) && !empty($value))
		{
			json_decode($value);
			return json_last_error() === JSON_ERROR_NONE;
		}

		return false;
	}
}