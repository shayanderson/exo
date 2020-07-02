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
 * Regex rule
 *
 * @author Shay Anderson
 * #docs
 */
class Regex extends \Exo\Validator\Rule
{
	protected $message = 'must match regular expression';
	private $regex;

	public function __construct(string $regex)
	{
		$this->regex = $regex;
	}

	public function validate($value): bool
	{
		return preg_match($this->regex, (string)$this->scalarOrNull($value)) > 0;
	}
}