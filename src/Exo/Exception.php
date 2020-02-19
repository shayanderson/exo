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
 * Exception
 *
 * @author Shay Anderson
 * #docs
 */
class Exception extends \Exception
{
	protected $code = 500; #next set as Response::code
	protected $data;

	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null,
		array $data = null)
	{
		parent::__construct($message, $code, $previous);
		$this->data = $data;
	}

	final public function getArgs(): ?array
	{
		return ( $this->getTrace()[0]['args'] ?? null );
	}

	final public function getData(): ?array
	{
		return $this->data;
	}

	final public function getMethod(): ?string
	{
		if(( $class = ( $this->getTrace()[0]['class'] ?? null )))
		{
			return $class . ( $this->getTrace()[0]['type'] ?? null )
				. ( $this->getTrace()[0]['function'] ?? null ) . '()';
		}

		return null;
	}

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
			$info['code'] = 500; #next set Response::code
		}

		if(method_exists($th, 'getMethod'))
		{
			$info['source'] = $th->getMethod();
		}

		$info['message'] = $th->getMessage();

		if(method_exists($th, 'getData') && $th->getData())
		{
			$info['data'] = $th->getData();
		}

		$handler($info, $th);
	}
}