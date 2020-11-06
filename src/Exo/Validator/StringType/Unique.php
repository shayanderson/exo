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

namespace Exo\Validator\StringType;

/**
 * Unique rule
 *
 * @author Shay Anderson
 */
class Unique extends \Exo\Validator\Rule
{
	/**
	 * Callback
	 *
	 * @var callable
	 */
	private $callback;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'must be unique';

	/**
	 * Init
	 *
	 * @param callable $callback
	 */
	public function __construct(callable $callback)
	{
		$this->callback = $callback;
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return (bool)($this->callback)($value);
	}
}