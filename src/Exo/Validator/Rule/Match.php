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
 * Match rule
 *
 * @author Shay Anderson
 * #docs
 */
class Match extends \Exo\Validator\Rule
{
	private $caseSensitive = true;
	private $compareValue;
	protected $message = 'values must be equal';

	public function __construct($compareValue, bool $caseSensitive = true)
	{
		$this->compareValue = $compareValue;
		$this->caseSensitive = $caseSensitive;
	}

	public function validate($value): bool
	{
		if(!is_scalar($value) && !is_scalar($this->compareValue))
		{
			return $value == $value;
		}

		if(!is_scalar($value) || !is_scalar($this->compareValue))
		{
			return false;
		}

		$func = $this->caseSensitive ? 'strcmp' : 'strcasecmp';

		return $func((string)$this->scalarOrNull($value),
			(string)$this->scalarOrNull($this->compareValue)) === 0;
	}
}