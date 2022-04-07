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

namespace Exo;

/**
 * Exception
 *
 * @author Shay Anderson
 */
class Exception extends \Exception
{
	/**
	 * Status code
	 *
	 * @var int
	 */
	protected $code = 500;

	/**
	 * Context
	 *
	 * @var array
	 */
	protected $context;

	/**
	 * Init
	 *
	 * @param string $message
	 * @param int $code
	 * @param array $context
	 * @param \Throwable $previous
	 */
	public function __construct(string $message = '', array $context = null, int $code = 0,
		\Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->context = $context;
	}

	/**
	 * Args getter
	 *
	 * @return array|null
	 */
	final public function getArgs(): ?array
	{
		return ( $this->getTrace()[0]['args'] ?? null );
	}

	/**
	 * Context getter
	 *
	 * @return array|null
	 */
	final public function getContext(): ?array
	{
		return $this->context;
	}

	/**
	 * Method getter
	 *
	 * @return string|null
	 */
	final public function getMethod(): ?string
	{
		if(($class = ( $this->getTrace()[0]['class'] ?? null )))
		{
			return $class . ( $this->getTrace()[0]['type'] ?? null )
				. ( $this->getTrace()[0]['function'] ?? null ) . '()';
		}
		else if(($func = ( $this->getTrace()[0]['function'] ?? null )))
		{
			return $func . '()';
		}

		return null;
	}

	/**
	 * Handle an exception
	 *
	 * @param \Throwable $th
	 * @param callable $handler
	 * @return void
	 */
	public static function handle(\Throwable $th, callable $handler): void
	{
		$info = [
			'type' => get_class($th)
		];

		if($th->getCode())
		{
			$info['code'] = $th->getCode();
		}
		else
		{
			$info['code'] = 500;
		}

		if(method_exists($th, 'getMethod'))
		{
			$info['source'] = $th->getMethod();
		}

		$info['message'] = $th->getMessage();

		if(method_exists($th, 'getContext') && $th->getContext())
		{
			$info['context'] = $th->getContext();
		}

		$handler($info);
	}
}