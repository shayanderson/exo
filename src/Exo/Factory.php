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
 * @method \Exo\Factory getInstance()
 * @method \Exo\Request request()
 */
class Factory extends Factory\Singleton
{
	private static $map = [
	];

	private static $map_singleton = [
		'request' => '\Exo\Request'
	];

	public function __call($method, $args)
	{
		if(isset(self::$map[$method]))
		{

		}

		if(isset(self::$map_singleton[$method]))
		{
			return (self::$map_singleton[$method])::getInstance();
		}

		throw new Exception("Invalid \Exo\Factory method \"{$method}\"");
	}
}