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

use Exo\Exception;
use Exo\Logger\Record;

/**
 * Logger
 *
 * @author Shay Anderson
 * #docs
 */
class Logger
{
	const LEVEL_DEBUG = 1;
	const LEVEL_INFO = 2;
	const LEVEL_WARNING = 3;
	const LEVEL_ERROR = 4;
	const LEVEL_CRITICAL = 5;

	private $channel;

	private static $levels = [
		self::LEVEL_DEBUG => 'DEBUG',
		self::LEVEL_INFO => 'INFO',
		self::LEVEL_WARNING => 'WARNING',
		self::LEVEL_ERROR => 'ERROR',
		self::LEVEL_CRITICAL => 'CRITICAL'
	];

	private static $handlers = [];

	public function __construct(string $channel = null)
	{
		if($channel !== null)
		{
			$this->channel = $channel;
		}
	}

	public function __get(string $channel): self
	{
		if(!$this->channel)
		{
			$this->channel = $channel;
		}
		else
		{
			#todo throw exception -- channel already exists
		}

		return $this;
	}

	public function critical(?string $message, ...$context): self
	{
		$this->record(self::LEVEL_CRITICAL, $message, $context);
		return $this;
	}

	public function debug(?string $message, ...$context): self
	{
		$this->record(self::LEVEL_DEBUG, $message, $context);
		return $this;
	}

	public function error(?string $message, ...$context): self
	{
		$this->record(self::LEVEL_ERROR, $message, $context);
		return $this;
	}

	public static function getLevelName(int $level): string
	{
		if(!isset(self::$levels[$level]))
		{
			throw new Exception('Invalid level: ' . $level);
		}

		return self::$levels[$level];
	}

	public static function handler(\Exo\Logger\Handler $handler): void
	{
		self::$handlers[] = $handler;
	}

	public function info(?string $message, ...$context): self
	{
		$this->record(self::LEVEL_INFO, $message, $context);
		return $this;
	}

	private function record(int $level, ?string $message, array $context): void
	{
		$record = new Record($level, $message, $context, $this->channel);

		/* @var $handler \Exo\Logger\Handler */
		foreach(self::$handlers as $handler)
		{
			if($handler->isHandling($record) && $handler->write($record) === true) // interrupt
			{
				break;
			}
		}
	}

	public function warning(?string $message, ...$context): self
	{
		$this->record(self::LEVEL_WARNING, $message, $context);
		return $this;
	}
}