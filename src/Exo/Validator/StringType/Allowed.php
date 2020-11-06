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

namespace Exo\Validator\StringType;

/**
 * Allowed rule
 *
 * @author Shay Anderson
 */
class Allowed extends \Exo\Validator\Rule
{
	/**
	 * List
	 *
	 * @var array
	 */
	private $list;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'must be allowed';

	/**
	 * Init
	 *
	 * @param array $list
	 */
	public function __construct(array $list)
	{
		$this->list = $list;
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		return in_array($value, $this->list);
	}
}