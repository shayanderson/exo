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

namespace Exo\App;

/**
 * CLI
 *
 * @author Shay Anderson
 */
class Cli extends \Exo\System\Singleton
{
	/**
	 * Args
	 *
	 * @var array
	 */
	private $args;

	/**
	 * Args map
	 *
	 * @var array
	 */
	private $map = [
		0 => 'SCRIPT',
		1 => 'COMMAND'
	];

	/**
	 * Confirm
	 *
	 * @param string $allow
	 * @return bool
	 */
	public function confirm(string $allow): bool
	{
		return trim((string)fgets(STDIN)) === $allow;
	}

	/**
	 * Arg getter
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get(string $key)
	{
		if(!$this->has($key))
		{
			return null;
		}

		return $this->getArgs()[$key];
	}

	/**
	 * Args getter
	 *
	 * @return array
	 */
	public function getArgs(): array
	{
		if($this->args === null)
		{
			if(isset($_SERVER['argv']))
			{
				foreach($_SERVER['argv'] as $k => $v)
				{
					if(isset($this->map[$k])) // mapped
					{
						$this->args[$this->map[$k]] = $v;
					}
					else if(( $pos = strpos($v, '=') ) !== false) // key/value
					{
						$this->args[substr($v, 0, $pos)] = substr($v, ++$pos);
					}
					else // key only
					{
						$this->args[$v] = true;
					}
				}
			}
			else
			{
				$this->args = [];
			}
		}

		return $this->args;
	}

	/**
	 * Args map getter
	 *
	 * @return array
	 */
	public function getMap(): array
	{
		return $this->map;
	}

	/**
	 * Check if arg key exists
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has(string $key): bool
	{
		return isset($this->getArgs()[$key]);
	}

	/**
	 * Check if interface type is CLI
	 *
	 * @return bool
	 */
	final public static function isCli(): bool
	{
		return PHP_SAPI === 'cli';
	}

	/**
	 * Args map setter
	 *
	 * @param array $map
	 * @return void
	 */
	public function map(array $map): void
	{
		$this->map = $map;
	}

	/**
	 * Output helper
	 *
	 * @param string|null $message
	 * @return \Exo\App\Cli\Output
	 */
	public function output($message = null): Cli\Output
	{
		if(func_num_args() > 0)
		{
			return Cli\Output::getInstance()->output($message);
		}

		return Cli\Output::getInstance();
	}
}