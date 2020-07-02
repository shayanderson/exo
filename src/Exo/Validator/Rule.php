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
 * Validator rule
 *
 * @author Shay Anderson
 * #docs
 */
abstract class Rule implements RuleInterface
{
	protected $message = 'unknown error';

	public function getMessage(): string
	{
		return $this->message;
	}

	protected function scalarOrNull($value)
	{
		if(is_scalar($value))
		{
			return $value;
		}

		return null;
	}

	public function setMessage(string $message): void
	{
		$this->message = $message;
	}
}