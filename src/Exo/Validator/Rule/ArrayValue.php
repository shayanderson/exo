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
 * Array rule
 *
 * @author Shay Anderson
 * #docs
 */
class ArrayValue extends \Exo\Validator\Rule
{
	protected $maxDepth;
	protected $message = 'must be an array with depth of %d';

	public function __construct(int $maxDepth = 1)
	{
		$this->maxDepth = $maxDepth;
		$this->message = sprintf($this->message, $this->maxDepth);
	}

	private static function arrayDepth(array $array): int
	{
		$max = $depth = 1;

		foreach($array as $a)
		{
			if(is_array($a))
			{
				$depth = self::arrayDepth($a) + 1;

				if($depth > $max)
				{
					$max = $depth;
				}
			}
		}

		return $max;
	}

	public function validate($value): bool
	{
		if(!is_array($value))
		{
			return false;
		}

		$depth = self::arrayDepth($value);

		return $depth === $this->maxDepth;
	}
}