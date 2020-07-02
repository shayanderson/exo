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

namespace Exo\Logger\Handler;

use Exo\Logger;

/**
 * Runtime logger handler
 *
 * @author Shay Anderson
 * #docs
 *
 * #todo rm:
 */
class Runtime extends \Exo\Logger\Handler
{
	private $log = [];

	public function __construct($maxRecords = 1000, int $level = Logger::LEVEL_DEBUG)
	{
		parent::__construct($level);

		#todo implement max records
	}

	public function close()
	{
		return $this->log;
	}

	public function write(\Exo\Logger\Record $record)
	{
		$this->log[] = $record;
	}
}