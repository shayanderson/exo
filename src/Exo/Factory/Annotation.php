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

use Exo\Exception;

/**
 * Annotation factory
 *
 * @author Shay Anderson
 */
abstract class Annotation extends Singleton
{
	/**
	 * Getter
	 *
	 * @param string $name
	 * @return \Exo\Factory\classes
	 * @throws \Exo\Exception (on class not found)
	 */
	final public function __get(string $name)
	{
		$classes = &static::classes();

		if(!$classes)
		{
			$classes = &self::getClassesFromAnnotations();
		}

		if(!isset($classes[$name]))
		{
			throw new Exception('Failed to find class from property "' . $name
				. '" in Annotation class "' . static::class . '"');
		}

		// singleton
		if((new \ReflectionClass($classes[$name]))->isSubclassOf(\Exo\Factory\Singleton::class))
		{
			return ($classes[$name])::getInstance();
		}

		return new $classes[$name];
	}

	/**
	 * Classes from annotations getter
	 *
	 * @staticvar array $classes
	 * @return array
	 */
	final protected static function &getClassesFromAnnotations(): array
	{
		$classes = [];

		// parse class annotations
		preg_match_all('/@property\s([^\s]+)\s\$([\w]+)/', // match '@property [class] $[name]'
			(new \ReflectionClass(static::class))->getDocComment(), $m);

		foreach($m[1] as $k => $v)
		{
			$classes[$m[2][$k] ?? null] = $v;
		}

		return $classes;
	}

	/**
	 * Classes getter (overridable)
	 *
	 * @return array
	 */
	protected static function &classes(): array
	{
		static $classes = [];
		return $classes;
	}
}