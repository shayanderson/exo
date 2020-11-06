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

namespace Exo\App\Http\Exception;

/**
 * HTTP method not allowed exception
 *
 * @author Shay Anderson
 */
class HttpMethodNotAllowedException extends HttpException
{
	/**
	 * Status code
	 *
	 * @var int
	 */
	protected $code = 405;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'Method not allowed';
}