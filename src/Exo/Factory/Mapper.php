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

namespace Exo\Factory;

use Exo\Exception;

/**
 * Mapper factory
 *
 * @author Shay Anderson
 */
abstract class Mapper extends Singleton
{
	/**
	 * Call
	 *
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 * @throws \Exo\Exception (on class does not exist in classes)
	 */
	public function __call(string $name, array $args)
	{
		$classes = &static::classes();

		if(!isset($classes[$name]))
		{
			throw new Exception('Failed to find class from method "' . $name . '()" in Mapper'
				. ' class "' . static::class . '"');
		}

		// singleton
		if((new \ReflectionClass($classes[$name]))->isSubclassOf(\Exo\Factory\Singleton::class))
		{
			return ($classes[$name])::getInstance();
		}

		return new $classes[$name](...$args);
	}

	/**
	 * Classes getter
	 *
	 * @return array
	 */
	abstract protected static function &classes(): array;
}