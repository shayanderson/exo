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

namespace Exo\Logger;

use Exo\Exception;
use Exo\Logger;

/**
 * Logger record
 *
 * @author Shay Anderson
 */
class Record
{
	/**
	 * Channel
	 *
	 * @var string|null
	 */
	public $channel;

	/**
	 * Context
	 *
	 * @var array
	 */
	public $context;

	/**
	 * Logger level
	 *
	 * @var int
	 */
	public $level;

	/**
	 * Logger level name
	 *
	 * @var string
	 */
	public $levelName;

	/**
	 * Message
	 *
	 * @var string|null
	 */
	public $message;

	/**
	 * Unix timestamp
	 *
	 * @var int
	 */
	public $timestamp;

	/**
	 * Init
	 *
	 * @param int $level
	 * @param string|null $message
	 * @param mixed $context
	 * @param string|null $channel
	 */
	public function __construct(int $level, ?string $message, $context, ?string $channel)
	{
		if(!$message && !$context && !$channel)
		{
			throw new Exception('Logger record cannot have empty message, context and channel');
		}

		$this->level = $level;
		$this->levelName = Logger::getLevelName($level);
		$this->message = $message;
		$this->context = $context;
		if($channel !== '')
		{
			$this->channel = $channel;
		}
		$this->timestamp = time();
	}
}