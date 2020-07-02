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

namespace Exo\Model;

/**
 * Store interface
 *
 * @author Shay Anderson
 * #docs
 */
interface StoreInterface
{
	public static function getLastCommand(): ?string;
	public static function getLastCommandContext(): array;
	public static function getLastCommandError(): ?string;
}