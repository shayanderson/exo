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

namespace Exo\Factory;

/**
 * Singleton factory
 *
 * @author Shay Anderson
 */
abstract class Singleton
{
	/**
	 * Protected
	 */
	protected function __construct() {}

	/**
	 * Not allowed
	 */
	final private function __clone() {}

	/**
	 * Not allowed
	 */
	final private function __wakeup() {}

	/**
	 * Instance getter
	 *
	 * @return \Exo\Factory\Singleton
	 */
	final public static function getInstance(): Singleton
	{
		$class = static::class;
		$instances = &static::instances();

		if(!isset($instances[$class]))
		{
			$instances[$class] = new static;
		}

		return $instances[$class];
	}

	/**
	 * Instances getter (overridable)
	 *
	 * @return array
	 */
	protected static function &instances(): array
	{
		static $instances = [];
		return $instances;
	}
}