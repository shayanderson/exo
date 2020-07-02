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
 * In list rule
 *
 * @author Shay Anderson
 * #docs
 */
class In extends \Exo\Validator\Rule
{
	private $list;
	protected $message = 'must be in list';

	public function __construct(...$list)
	{
		if(is_array(current($list)))
		{
			$this->list = current($list);
		}
		else
		{
			$this->list = $list;
		}
	}

	public function validate($value): bool
	{
		return in_array($value, $this->list);
	}
}