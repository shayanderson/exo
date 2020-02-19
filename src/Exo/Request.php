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

namespace Exo;

/**
 * Request
 *
 * @author Shay Anderson
 * #docs
 */
class Request extends System\Singleton
{
	public function server($key)
	{
		return $_SERVER[$key] ?? null; #next filter these values
	}

	public function uri(bool $query_string = true): string
	{
		$uri = $this->server('REQUEST_URI');

		// strip query string
		return !$query_string && ( $pos = strpos($uri, '?') ) !== false
			? substr($uri, 0, $pos) : $uri;
	}
}