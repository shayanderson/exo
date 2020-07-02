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

namespace Exo\Factory;

/**
 * Singleton
 *
 * @author Shay Anderson
 * #docs
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
	public static function getInstance(): Singleton
	{
		$class = static::class;
		$instances = &static::getInstances();

		if(!isset($instances[$class]))
		{
			$instances[$class] = new static;
		}

		return $instances[$class];
	}

	/**
	 * Instances getter
	 *
	 * @return array
	 */
	abstract protected static function &getInstances(): array;
}