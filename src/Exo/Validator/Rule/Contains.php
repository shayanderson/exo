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
 * Contains rule
 *
 * @author Shay Anderson
 * #docs
 */
class Contains extends \Exo\Validator\Rule
{
	private $containsValue;
	private $isCaseSensitive;
	protected $message = 'must contain value';

	public function __construct($containsValue, bool $caseSensitive = true)
	{
		$this->containsValue = $containsValue;
		$this->isCaseSensitive = $caseSensitive;
	}

	public function validate($value): bool
	{
		return $this->isCaseSensitive
			? strpos($value, $this->containsValue) !== false
			: stripos($value, $this->containsValue) !== false;
	}
}