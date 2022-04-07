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

namespace Exo;

/**
 * Event trait
 *
 * @author Shay Anderson
 */
trait Event
{
	/**
	 * Emit an event
	 *
	 * @param string $name
	 * @param array $args
	 * @return void
	 */
	public static function emitEvent(string $name, array $args = null): void
	{
		System::debug(__METHOD__, ['name' => $name, $args]);
		$events = &self::events();

		if(isset($events[$name]))
		{
			foreach($events[$name] as $cb)
			{
				if($cb($args) === true) // interrupt
				{
					return;
				}
			}
		}
	}

	/**
	 * Events getter
	 *
	 * @return array
	 */
	abstract protected static function &events(): array;

	/**
	 * Bind event callable
	 *
	 * @param string $name
	 * @param callable $callback
	 * @return void
	 */
	public static function onEvent(string $name, callable $callback): void
	{
		System::debug(__METHOD__, ['name' => $name]);
		$events = &self::events();
		$events[$name][] = $callback;
	}
}