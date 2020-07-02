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

namespace Exo\Validator;

/**
 * Rule interface
 *
 * @author Shay Anderson
 * #docs
 */
interface RuleInterface
{
	public function getMessage(): string;
	public function setMessage(string $message): void;
	public function validate($value): bool;
}