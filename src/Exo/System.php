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
 * System
 *
 * @author Shay Anderson
 */
class System
{
	/**
	 * Log debug
	 *
	 * @param string|null $message
	 * @param mixed $context
	 * @return void
	 */
	public static function debug(?string $message, ...$context): void
	{
		if(self::isDebugging())
		{
			Factory::getInstance()->logger('exo')->debug($message, ...$context);
		}
	}

	/**
	 * Check if debugging
	 *
	 * @return bool
	 */
	public static function isDebugging(): bool
	{
		return defined('EXO_DEBUG') && EXO_DEBUG;
	}

	/**
	 * Variable printer
	 *
	 * @param mixed $values
	 * @return void
	 */
	public static function pa(...$values): void
	{
		if(!count($values))
		{
			$values = [null];
		}

		foreach($values as $v)
		{
			echo is_scalar($v) || $v === null
				? $v . ( PHP_SAPI === 'cli' ? PHP_EOL : '<br />' )
				: ( PHP_SAPI === 'cli' ? print_r($v, true) : '<pre>' . print_r($v, true) . '</pre>' );
		}
	}
}