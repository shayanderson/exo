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

namespace Exo\Validator\StringType;

/**
 * Alpha rule
 *
 * @author Shay Anderson
 */
class Alpha extends \Exo\Validator\Rule
{
	/**
	 * Allow whitespaces
	 *
	 * @var bool
	 */
	private $allowWhitespaces;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'must only contain alphabetic characters';

	/**
	 * Init
	 *
	 * @param bool $allowWhitespaces
	 */
	public function __construct(bool $allowWhitespaces = false)
	{
		$this->allowWhitespaces = $allowWhitespaces;
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return $this->allowWhitespaces
			? preg_match('/^[a-zA-Z\s]+$/', (string)$value) === 1
			: ctype_alpha((string)$value);
	}
}