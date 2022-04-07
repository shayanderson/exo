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

namespace Exo\Validator\StringType;

/**
 * Password rule
 *
 * @author Shay Anderson
 */
class Password extends \Exo\Validator\Rule
{
	/**
	 * Hash
	 *
	 * @var string
	 */
	private $hash;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'passwords must be equal';

	/**
	 * Init
	 *
	 * @param string $hash
	 */
	public function __construct(string $hash)
	{
		$this->hash = $hash;
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return password_verify((string)$value, $this->hash);
	}
}