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
 * Event trait
 *
 * @author Shay Anderson
 * #docs
 */
trait Event
{
	public static function emitEvent(string $id, ...$args)
	{
		Factory::debug(__METHOD__, ['id' => $id, $args]);
		$events = &self::getEvents();

		if(isset($events[$id]))
		{
			foreach($events[$id] as $cb)
			{
				if($cb(...$args) === true) // interrupt
				{
					return;
				}
			}
		}
	}

	abstract protected static function &getEvents(): array;

	public static function onEvent(string $id, callable $callback)
	{
		Factory::debug(__METHOD__, ['id' => $id]);
		$events = &self::getEvents();
		$events[$id][] = $callback;
	}
}