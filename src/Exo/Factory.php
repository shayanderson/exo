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

namespace Exo;

/**
 * Factory
 *
 * @author Shay Anderson #docs
 *
 * @method \Exo\Logger logger()
 * @method \Exo\Map map(array $map = null)
 * @method \Exo\Options options(array $options = null)
 * @method \Exo\Request request()
 * @method \Exo\Share share()
 * @method \Exo\Validator validator()
 */
class Factory extends Factory\Mapper
{
	private static $classes = [
		'logger' => '\Exo\Logger',
		'map' => '\Exo\Map',
		'options' => '\Exo\Options',
		'request' => '\Exo\Request',
		'share' => '\Exo\Share',
		'validator' => '\Exo\Validator'
	];

	private static $instances = [];

	public static function debug(?string $message, ...$context)
	{
		if(defined('EXO_DEBUG') && EXO_DEBUG)
		{
			self::getInstance()->logger()->exo->debug($message, ...$context);
		}
	}

	protected static function &getClasses(): array
	{
		return self::$classes;
	}

	protected static function &getInstances(): array
	{
		return self::$instances;
	}
}