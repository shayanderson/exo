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

namespace Exo\Exception;

/**
 * HTTP not found exception
 *
 * @author Shay Anderson
 * #docs
 */
class HttpNotFoundException extends HttpException
{
	protected $code = 404; #next set Response::code

	public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
	{
		parent::__construct('Route not found', $code, $previous,
			['route' => \Exo\System::getInstance()->request()->uri()]);
	}
}