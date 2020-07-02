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
 * URL rule
 *
 * @author Shay Anderson
 * #docs
 */
class Url extends \Exo\Validator\Rule
{
	protected $message = 'must be valid URL';

	public function validate($value): bool
	{
		return filter_var($value, FILTER_VALIDATE_URL) !== false;
	}
}