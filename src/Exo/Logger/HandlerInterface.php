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
 * #docs
 */
interface HandlerInterface
{
	public function close();
	public function isHandling(\Exo\Logger\Record $record): bool;
	public function write(\Exo\Logger\Record $record);
	/* #todo implement
	public function writeMany(array $records): void;
	 */
}