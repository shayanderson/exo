<?php
/**
 * Exo // Next-Gen Eco Framework
 *
 * @package Exo
 * @copyright 2015-2022 Shay Anderson <https://www.shayanderson.com>
 * @license MIT License <https://github.com/shayanderson/exo/blob/master/LICENSE>
 * @link <https://github.com/shayanderson/exo>
 */
declare(strict_types=1);

namespace Exo\System;

/**
 * Singleton
 *
 * @author Shay Anderson
 */
class Singleton extends \Exo\Factory\Singleton
{
	/**
	 * Instances
	 *
	 * @var array
	 */
	private static $instances = [];

	/**
	 * Instances getter
	 *
	 * @return array
	 */
	protected static function &instances(): array
	{
		return self::$instances;
	}
}