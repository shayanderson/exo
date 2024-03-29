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
 * IPv4 rule
 *
 * @author Shay Anderson
 */
class Ipv4 extends \Exo\Validator\Rule
{
	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'must be valid IPv4 address';

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
	}
}