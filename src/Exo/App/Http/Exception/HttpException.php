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
 * HTTP exception
 *
 * @author Shay Anderson
 */
class HttpException extends \Exo\Exception
{
	/**
	 * Init
	 *
	 * @param string $message
	 * @param array $context
	 * @param int $code
	 * @param \Throwable $previous
	 */
	public function __construct(string $message = '', array $context = [], int $code = 0,
		\Throwable $previous = null)
	{
		$req = \Exo\App\Http\Request::getInstance();

		parent::__construct($message, $context += [
			'httpRequest' => [
				'headers' => $req->headers(),
				'host' => $req->host(),
				'ipAddress' => $req->ipAddress(),
				'method' => $req->method(),
				'path' => $req->path(),
				'port' => $req->port(),
				'queryString' => $req->queryString(),
				'scheme' => $req->scheme()
			]
		], $code, $previous);
	}
}