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
 * Hash rule
 *
 * @author Shay Anderson
 * #docs
 */
class Hash extends \Exo\Validator\Rule
{
	private $knownHash;
	protected $message = 'hashes must be equal';

	public function __construct(string $knownHash)
	{
		$this->knownHash = $knownHash;
	}

	public function validate($value): bool
	{
		if(!is_string($value))
		{
			return false;
		}

		return hash_equals($this->knownHash, $value);
	}
}