<?php
/**
 * Exo // Next-Gen Eco Framework
 *
 * @package Exo
 * @copyright 2015-2021 Shay Anderson <https://www.shayanderson.com>
 * @license MIT License <https://github.com/shayanderson/exo/blob/master/LICENSE>
 * @link <https://github.com/shayanderson/exo>
 */
declare(strict_types=1);

namespace Exo\App\Http\Request;

/**
 * Request input
 *
 * @author Shay Anderson
 */
class Input extends AbstractInput
{
	/**
	 * Input type
	 */
	const TYPE = INPUT_POST;

	/**
	 * Input array getter
	 *
	 * @return array
	 */
	protected static function &getInputArray(): array
	{
		return $_POST;
	}
}