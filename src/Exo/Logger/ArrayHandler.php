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

use Exo\Logger;

/**
 * Runtime logger handler
 *
 * @author Shay Anderson
 */
class ArrayHandler extends Handler
{
	/**
	 * Log
	 *
	 * @var array
	 */
	private $log = [];

	/**
	 * Max records
	 *
	 * @var int
	 */
	private $maxRecords;

	/**
	 * Init
	 *
	 * @param int $maxRecords
	 * @param int $level
	 * @param array $channelFilter
	 */
	public function __construct(int $maxRecords = 1000, int $level = Logger::LEVEL_DEBUG,
		array $channelFilter = null)
	{
		parent::__construct($level, $channelFilter);
		$this->maxRecords = $maxRecords;
	}

	/**
	 * Log getter
	 *
	 * @return array
	 */
	public function close()
	{
		return $this->log;
	}

	/**
	 * Write to log
	 *
	 * @param \Exo\Logger\Record $record
	 * @return void
	 */
	public function write(\Exo\Logger\Record $record): void
	{
		if($this->isHandling($record))
		{
			$this->log[] = $record;

			if(count($this->log) > $this->maxRecords) // limit records
			{
				array_shift($this->log);
			}
		}
	}
}