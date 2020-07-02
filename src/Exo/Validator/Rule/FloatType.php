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
 * Float type
 *
 * @author Shay Anderson
 * #docs
 */
class FloatType extends \Exo\Validator\Rule
{
	protected $message = 'must be PHP primitive type float';

	public function validate($value): bool
	{
		return is_float($value);
	}
}