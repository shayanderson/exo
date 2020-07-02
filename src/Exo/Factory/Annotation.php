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
 * Annotation
 *
 * @author Shay Anderson
 * #docs
 */
abstract class Annotation extends Singleton
{
	public function __get(string $name)
	{
		$classes = &static::getClasses();

		if(!$classes)
		{
			// parse class annotations
			preg_match_all('/@property\s([^\s]+)\s\$([\w]+)/', // match '@property [class] $[name]'
				(new \ReflectionClass(static::class))->getDocComment(), $m);

			foreach($m[1] as $k => $v)
			{
				$classes[$m[2][$k] ?? null] = $v;
			}
		}

		if(!isset($classes[$name]))
		{
			throw new Exception('Failed to find class from property "' . $name
				. '" in Annotation class "' . static::class . '"');
		}

		// singleton
		if((new \ReflectionClass($classes[$name]))->isSubclassOf('\Exo\Factory\Singleton'))
		{
			return ($classes[$name])::getInstance();
		}

		return new $classes[$name];
	}

	/**
	 * Classes getter
	 *
	 * @return array
	 */
	abstract protected static function &getClasses(): array;
}