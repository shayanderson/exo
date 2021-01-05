<?php
/**
 * Exo // Next-Gen Eco Framework
 *
 * @package Exo
 * @copyright 2015-2021 Shay Anderson <https://www.shayanderson.com>
 * @license MIT License <https://github.com/shayanderson/exo/blob/master/LICENSE>
 * @link <https://github.com/shayanderson/exo>
 */
declare(strict_types=1);

namespace Exo\App\Http;

use Exo\Exception;

/**
 * Request
 *
 * @author Shay Anderson
 */
class Request extends \Exo\System\Singleton
{
	/**
	 * Read raw data from request body
	 *
	 * @return string
	 */
	public function body(bool $convertHtmlEntities = true): string
	{
		return $convertHtmlEntities
			? html_entity_decode(file_get_contents('php://input'))
			: file_get_contents('php://input');
	}

	/**
	 * Content-type value getter
	 *
	 * @return string
	 */
	public function contentType(): string
	{
		return $this->header('content-type') ?: '';
	}

	/**
	 * Cookie object getter
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return \Exo\App\Http\Request\Cookie
	 */
	public function cookie(string $key, $default = null): Request\Cookie
	{
		return new Request\Cookie($key, $default);
	}

	/**
	 * Check if header key exists
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasHeader(string $key): bool
	{
		return isset($this->headers()[strtolower($key)]);
	}

	/**
	 * Header value getter
	 *
	 * @param string $key
	 * @return string
	 */
	public function header(string $key): string
	{
		return $this->hasHeader($key)
			? trim((string)$this->headers()[strtolower($key)])
			: '';
	}

	/**
	 * All headers as array getter
	 *
	 * @staticvar array $h
	 * @return array
	 */
	public function headers(): array
	{
		static $h;

		if(!$h)
		{
			// non-Apache
			if(!function_exists('getallheaders')
				&& !function_exists(__NAMESPACE__ . '\getallheaders'))
			{
				function getallheaders()
				{
					$h = [];
					foreach($_SERVER as $k => $v)
					{
						if(substr($k, 0, 5) === 'HTTP_')
						{
							$h[str_replace(' ', '-',
								ucwords(
									strtolower(
										str_replace('_', ' ',
											substr($k, 5)
										)
									)
								)
							)] = filter_var($k, FILTER_SANITIZE_STRING);
						}
					}
					return $h;
				}
			}
			$h = getallheaders();
			// convert all to lowercase
			foreach($h as $k => $v)
			{
				unset($h[$k]);
				$h[strtolower($k)] = filter_var($v, FILTER_SANITIZE_STRING);
			}
		}

		return $h;
	}

	/**
	 * HTTP host value getter (ex: "www.example.com")
	 *
	 * @return string
	 */
	public function host(): string
	{
		if(!$host = filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_STRING))
		{
			if(!$host = filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING))
			{
				$host = (string)filter_input(INPUT_SERVER, 'SERVER_ADDR', FILTER_SANITIZE_STRING);
			}
		}

		return $host ?: '';
	}

	/**
	 * Input object getter
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return \Exo\App\Http\Request\Input
	 */
	public function input(string $key, $default = null): Request\Input
	{
		return new Request\Input($key, $default);
	}

	/**
	 * Remote IP address getter
	 *
	 * @return string
	 */
	public function ipAddress(): string
	{
		return (string)filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING);
	}

	/**
	 * Check if content-type is specific type
	 *
	 * @param string $contentType
	 * @return bool
	 */
	public function isContentType(string $contentType): bool
	{
		return $this->contentType() === $contentType;
	}

	/**
	 * Check if method is specific type
	 *
	 * @param string $method
	 * @return bool
	 */
	public function isMethod(string $method): bool
	{
		return $this->method() === $method;
	}

	/**
	 * Check if request is secure (HTTPS)
	 *
	 * @return bool
	 */
	public function isSecure(): bool
	{
		return strtoupper(
			(string)filter_input(INPUT_SERVER, 'HTTPS', FILTER_SANITIZE_STRING)
		) === 'ON';
	}

	/**
	 * JSON input helper
	 *
	 * @param bool $returnArray
	 * @return array|object
	 * @throws \Exo\Exception (on JSON decode error)
	 */
	public function &json(bool $returnArray = false)
	{
		$json = json_decode($this->body(), $returnArray);

		if(json_last_error() !== JSON_ERROR_NONE)
		{
			throw new Exception('Invalid JSON: ' . json_last_error_msg());
		}

		return $json;
	}

	/**
	 * Request method getter
	 *
	 * @return string
	 */
	public function method(): string
	{
		return (string)filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
	}

	/**
	 * URI path getter (ex: "/the/path")
	 *
	 * @return string
	 */
	public function path(): string
	{
		return parse_url(
			(string)filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING),
			PHP_URL_PATH
		);
	}

	/**
	 * URI path with query string getter (ex: "/the/path?x=1")
	 *
	 * @return string
	 */
	public function pathWithQueryString(): string
	{
		$qs = $this->queryString();
		return $this->path() . ( $qs ? '?' . $qs : null );
	}

	/**
	 * Port getter
	 *
	 * @return int
	 */
	public function port(): int
	{
		return (int)filter_input(INPUT_SERVER, 'SERVER_PORT', FILTER_SANITIZE_NUMBER_INT);
	}

	/**
	 * Query object getter
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return \Exo\App\Http\Request\Query
	 */
	public function query(string $key, $default = null): Request\Query
	{
		return new Request\Query($key, $default);
	}

	/**
	 * Query string getter (ex: "x=1&y=2")
	 *
	 * @return string
	 */
	public function queryString(): string
	{
		return (string)filter_input(INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_STRING);
	}

	/**
	 * URI scheme getter (ex: "http")
	 *
	 * @return string
	 */
	public function scheme(): string
	{
		return $this->isSecure() ? 'https' : 'http';
	}

	/**
	 * Session object getter
	 *
	 * @return \Exo\App\Http\Request\Session
	 */
	public function session(): Request\Session
	{
		return Request\Session::getInstance();
	}

	/**
	 * URI getter (ex: "http://example.com/example?key=x" or if custom port: "http://test.com:8080")
	 *
	 * @return string
	 */
	public function uri(): string
	{
		$scheme = $this->scheme();
		$port = $this->port();

		if(( $scheme === 'http' && $port === 80 ) || ( $scheme === 'https' && $port === 443 ))
		{
			$port = null;
		}

		return $scheme . '://' . $this->host() . ( $port ? ':' . $port : null )
			. $this->pathWithQueryString();
	}
}