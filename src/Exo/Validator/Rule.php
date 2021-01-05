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

namespace Exo\Validator;

/**
 * Validator type rule
 *
 * @author Shay Anderson
 */
abstract class Rule implements RuleInterface
{
	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'unknown error';

	/**
	 * Message getter
	 *
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * Message setter
	 *
	 * @param string $message
	 * @return void
	 */
	public function setMessage(string $message): void
	{
		$this->message = $message;
	}
}