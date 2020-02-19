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

namespace Exo\Exception;

/**
 * HTTP bad request exception
 *
 * @author Shay Anderson
 * #docs
 */
class HttpBadRequestException extends HttpException
{
	protected $code = 400; #next respose::code
}