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
 * Password rule
 *
 * @author Shay Anderson
 * #docs
 */
class Password extends \Exo\Validator\Rule
{
	private $hash;
	protected $message = 'passwords must be equal';

	public function __construct(string $hash)
	{
		$this->hash = $hash;
	}

	public function validate($value): bool
	{
		return password_verify($value, $this->hash);
	}
}