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

namespace Exo\Validator;

/**
 * Rule interface
 *
 * @author Shay Anderson
 */
interface RuleInterface
{
	/**
	 * Message getter
	 *
	 * @return string
	 */
	public function getMessage(): string;

	/**
	 * Message setter
	 *
	 * @param string $message
	 * @return void
	 */
	public function setMessage(string $message): void;

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool;
}