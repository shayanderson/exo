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

namespace Exo\Logger;

use Exo\Logger;

/**
 * Logger record
 *
 * @author Shay Anderson
 * #docs
 *
 * logger message props:
 *		message
 *		context
 *		level (int)
 *		level_name
 *		channel
 *		timestamp
 */
class Record
{
	public $channel;
	public $context;
	public $level;
	public $levelName;
	public $message;
	public $timestamp;

	public function __construct(int $level, ?string $message, array $context, ?string $channel)
	{
		$this->level = $level;
		$this->levelName = Logger::getLevelName($level);
		$this->message = $message;
		$this->context = $context;
		$this->channel = $channel;
		$this->timestamp = time();
	}
}