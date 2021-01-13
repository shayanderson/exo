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

namespace Exo\App\Http\Request;

/**
 * Request session flash
 *
 * @author Shay Anderson
 */
class SessionFlash extends \Exo\System\Singleton
{
	/**
	 * Session key
	 */
	const SESSION_KEY = '__EXO__';

	/**
	 * Data
	 *
	 * @var array
	 */
	private static $data;

	/**
	 * Session object
	 *
	 * @var \Exo\App\Http\Request\Session
	 */
	private $session;

	/**
	 * Init
	 */
	protected function __construct()
	{
		$this->session = Session::getInstance();

		// cache
		self::$data = $this->session->get(self::SESSION_KEY);
		if(self::$data === null)
		{
			self::$data = []; // init
		}

		$this->session->clear(self::SESSION_KEY);
	}

	/**
	 * Getter
	 *
	 * @param string $key
	 * @return mixed (null if not found)
	 */
	public function get(string $key)
	{
		return self::$data[$key] ?? null;
	}

	/**
	 * Check if key exists
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has(string $key): bool
	{
		return isset(self::$data[$key]) || array_key_exists($key, self::$data);
	}

	/**
	 * Setter
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function set(string $key, $value): void
	{
		$this->session->set(self::SESSION_KEY . '.' . $key, $value);
		self::$data[$key] = $value;
	}

	/**
	 * Array getter
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		return self::$data;
	}
}