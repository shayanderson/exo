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

namespace Exo\Validator\Rule;

/**
 * Alnum rule
 *
 * @author Shay Anderson
 * #docs
 */
class Alnum extends \Exo\Validator\Rule
{
	private $allowWhitespaces;
	protected $message = 'must contain only alphanumeric characters';

	public function __construct(bool $allowWhitespaces = false)
	{
		$this->allowWhitespaces = $allowWhitespaces;
	}

	public function validate($value): bool
	{
		return $this->allowWhitespaces
			? preg_match('/^[a-zA-Z0-9\s]+$/', (string)$this->scalarOrNull($value)) === 1
			: ctype_alnum((string)$this->scalarOrNull($value));
	}
}