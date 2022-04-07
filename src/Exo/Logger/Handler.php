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

namespace Exo\Logger;

use Exo\Logger;
use Exo\Map;

/**
 * Logger handler
 *
 * @author Shay Anderson
 */
abstract class Handler implements HandlerInterface
{
	/**
	 * Channel filter
	 *
	 * @var array
	 */
	protected $channelFilter;

	/**
	 * Logger level
	 *
	 * @var int
	 */
	protected $level;

	/**
	 * Init
	 *
	 * @param int $level
	 * @param array $channelFilter
	 */
	public function __construct(int $level = Logger::LEVEL_DEBUG, array $channelFilter = null)
	{
		$this->level = $level;
		$this->channelFilter = $channelFilter;
	}

	/**
	 * Check if handling record
	 *
	 * @param \Exo\Logger\Record $record
	 * @return bool
	 */
	public function isHandling(\Exo\Logger\Record $record): bool
	{
		if($this->channelFilter
			&& !Map::arrayFilterKeys([$record->channel => null], $this->channelFilter))
		{
			return false;
		}

		return $record->level >= $this->level;
	}
}