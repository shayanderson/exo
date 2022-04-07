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

namespace Exo\App\Http\Exception;

/**
 * HTTP unauthorized exception
 *
 * @author Shay Anderson
 */
class HttpUnauthorizedException extends HttpException
{
	/**
	 * Status code
	 *
	 * @var int
	 */
	protected $code = 401;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'Unauthorized';
}