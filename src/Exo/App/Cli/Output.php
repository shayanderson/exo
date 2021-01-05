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

namespace Exo\App\Cli;

use Exo\Exception;
use Exo\System;

/**
 * CLI output
 *
 * @author Shay Anderson
 *
 * @method self colorBlue($message = null)
 * @method self colorCyan($message = null)
 * @method self colorGray($message = null)
 * @method self colorGreen($message = null)
 * @method self colorLightBlue($message = null)
 * @method self colorLightCyan($message = null)
 * @method self colorLightGray($message = null)
 * @method self colorLightGreen($message = null)
 * @method self colorLightMagenta($message = null)
 * @method self colorLightRed($message = null)
 * @method self colorLightYellow($message = null)
 * @method self colorMagenta($message = null)
 * @method self colorRed($message = null)
 * @method self colorYellow($message = null)
 */
class Output extends \Exo\System\Singleton
{
	/**
	 * Buffer
	 *
	 * @var array
	 */
	private $buffer = [];

	/**
	 * Buffer key
	 *
	 * @var int
	 */
	private $bufferKey;

	/**
	 * Buffer key when prepending
	 *
	 * @var type
	 */
	private $bufferKeyPrepend;

	/**
	 * Current color
	 *
	 * @var int
	 */
	private $color;

	/**
	 * Color map
	 *
	 * @var array
	 */
	private static $colors = [
		'Blue' => 34,
			'LightBlue' => 94,
		'Cyan' => 36,
			'LightCyan' => 96,
		'Gray' => 90,
			'LightGray' => 37,
		'Green' => 32,
			'LightGreen' => 92,
		'Magenta' => 35,
			'LightMagenta' => 95,
		'Red' => 31,
			'LightRed' => 91,
		'Yellow' => 33,
			'LightYellow' => 93
	];

	/**
	 * Current input multiplier
	 *
	 * @var int
	 */
	private $indentMultiplier = 0;

	/**
	 * Buffering flag
	 *
	 * @var bool
	 */
	private $isBuffer = false;

	/**
	 * Newline flag
	 *
	 * @var bool
	 */
	private $isNewline = true;

	/**
	 * Printing flag
	 *
	 * @var bool
	 */
	private $isPrinting = false;

	/**
	 * Color methods
	 *
	 * @param string $name
	 * @param array $args
	 * @return self
	 * @throws \Exo\Exception (on undefined method)
	 */
	public function __call(string $name, $args): self
	{
		if(substr($name, 0, 5) === 'color')
		{
			$color = substr($name, 5);
			if(isset(self::$colors[$color]))
			{
				$this->color = self::$colors[$color];
				if(isset($args[0])) // message
				{
					return $this->output($args[0]);
				}

				return $this;
			}
		}

		throw new Exception('Call to undefined method ' . __CLASS__ . '::' . $name . '()');
	}

	/**
	 * Buffer getter
	 *
	 * @return array
	 */
	public function buffer(): array
	{
		return $this->buffer;
	}

	/**
	 * Enable buffering
	 *
	 * @return void
	 */
	public function enableBuffering(): void
	{
		$this->isBuffer = true;
	}

	/**
	 * Indent output
	 *
	 * @param string|null $message
	 * @return self
	 */
	public function indent($message = null): self
	{
		$this->indentMultiplier++;

		if($message !== null)
		{
			$this->output($message);
		}

		return $this;
	}

	/**
	 * Print an empty line
	 *
	 * @param int $multiplier
	 * @return self
	 */
	public function line(int $multiplier = 1): self
	{
		for($i = 0; $i < $multiplier; $i++)
		{
			$this->output('');
		}
		return $this;
	}

	/**
	 * Output
	 *
	 * @param string|null $message
	 * @return self
	 * @throws \Exo\Exception (on invalid message primitive type)
	 */
	public function output($message): self
	{
		if(is_object($message))
		{
			$message = (array)$message;
		}

		if(is_array($message))
		{
			$this->isPrinting = true;
			foreach($message as $v)
			{
				$this->output($v);
			}
			$this->isPrinting = false;
			$this->reset();
			return $this;
		}

		if(!is_scalar($message) && $message !== null)
		{
			throw new Exception('Invalid CLI print type');
		}

		if($this->color)
		{
			$message = "\033[{$this->color}m{$message}\033[0m";
		}

		if($this->indentMultiplier)
		{
			$message = str_repeat('    ', $this->indentMultiplier) . $message;
		}

		if($this->isBuffer)
		{
			if($this->bufferKey === null)
			{
				$this->bufferKey = 0;
			}

			if($this->bufferKeyPrepend)
			{
				$this->buffer[$this->bufferKeyPrepend] .= $message;
			}
			else
			{
				$this->buffer[++$this->bufferKey] = $message;
			}

			$this->bufferKeyPrepend = $this->isNewline ? null : $this->bufferKey;
		}
		else
		{
			if($this->isNewline)
			{
				System::pa($message);
			}
			else
			{
				echo $message;
			}
		}

		$this->reset();

		return $this;
	}

	/**
	 * Output without a newline
	 *
	 * @param string|null $message
	 * @return self
	 */
	public function prepend($message): self
	{
		$this->isNewline = false;
		return $this->output($message);
	}

	/**
	 * Internal reset props
	 *
	 * @return void
	 */
	private function reset(): void
	{
		if($this->isPrinting)
		{
			return;
		}

		$this->indentMultiplier = 0;
		$this->isNewline = true;
		$this->color = null;
	}
}