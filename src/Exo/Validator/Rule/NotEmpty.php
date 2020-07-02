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
 * Not empty rule
 *
 * @author Shay Anderson
 * #docs
 */
class NotEmpty extends \Exo\Validator\Rule
{
	protected $message = 'must not be empty';

	public function validate($value): bool
	{
		if(is_string($value))
		{
			return strlen(trim($value)) > 0;
		}

		return !empty($value);
	}
}