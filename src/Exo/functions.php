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

/**
 * Exo common functions
 *
 * @author Shay Anderson
 * #docs
 */

/**
 * Exo object getter
 *
 * @return \Exo\System
 */
function exo(): \Exo\System
{
	return \Exo\System::getInstance();
}