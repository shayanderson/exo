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

namespace Exo\ValidatorOld\Rule;

namespace Exo\Validator\StringType;

/**
 * Hash rule
 *
 * @author Shay Anderson
 */
class Hash extends \Exo\Validator\Rule
{
	/**
	 * Known hash
	 *
	 * @var string
	 */
	private $knownHash;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'hashes must be equal';

	/**
	 * Init
	 *
	 * @param string $knownHash
	 */
	public function __construct(string $knownHash)
	{
		$this->knownHash = $knownHash;
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		if(!is_string($value))
		{
			return false;
		}

		return hash_equals($this->knownHash, $value);
	}
}