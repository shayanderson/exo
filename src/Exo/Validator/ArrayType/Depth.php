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

namespace Exo\Validator\ArrayType;

/**
 * Depth rule
 *
 * @author Shay Anderson
 */
class Depth extends \Exo\Validator\Rule
{
	/**
	 * Depth
	 *
	 * @var int
	 */
	private $depth;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'must be an array with depth of %s';

	/**
	 * Init
	 *
	 * @param int $depth
	 */
	public function __construct(int $depth)
	{
		$this->depth = $depth;
	}

	/**
	 * Depth getter
	 *
	 * @param array $array
	 * @return int
	 */
	private static function depth(array $array): int
	{
		$max = $depth = 1;

		foreach($array as $a)
		{
			if(is_array($a))
			{
				$depth = self::depth($a) + 1;

				if($depth > $max)
				{
					$max = $depth;
				}
			}
		}

		return $max;
	}

	/**
	 * Message getter
	 *
	 * @return string
	 */
	public function getMessage(): string
	{
		return sprintf($this->message, $this->depth);
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return self::depth($value) === $this->depth;
	}
}