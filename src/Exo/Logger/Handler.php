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
 * Logger handler
 *
 * @author Shay Anderson
 * #docs
 */
abstract class Handler implements HandlerInterface
{
	protected $level;

	#todo add $excludeChannels = []; so can not do handling for specific channels like ['eco']
	public function __construct(int $level = Logger::LEVEL_DEBUG)
	{
		$this->level = $level;
	}

	public function isHandling(\Exo\Logger\Record $record): bool
	{
		return $record->level >= $this->level;
	}
}