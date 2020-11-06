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

/**
 * Logger handler interface
 *
 * @author Shay Anderson
 *
 * #next implement public function writeMany(array $records): void;
 */
interface HandlerInterface
{
	/**
	 * Close/end logging
	 *
	 * @return mixed
	 */
	public function close();

	/**
	 * Check if handling record
	 *
	 * @param \Exo\Logger\Record $record
	 * @return bool
	 */
	public function isHandling(\Exo\Logger\Record $record): bool;

	/**
	 * Write to log
	 *
	 * @param \Exo\Logger\Record $record
	 * @return void
	 */
	public function write(\Exo\Logger\Record $record): void;
}