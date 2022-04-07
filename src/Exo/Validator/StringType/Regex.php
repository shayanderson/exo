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

use Exo\Validator\Exception;

/**
 * Regex rule
 *
 * @author Shay Anderson
 */
class Regex extends \Exo\Validator\Rule
{
	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message = 'must match regular expression pattern';

	/**
	 * Pattern
	 *
	 * @var string
	 */
	private $pattern;

	/**
	 * Init
	 *
	 * @param string $pattern
	 */
	public function __construct(string $pattern)
	{
		$this->pattern = $pattern;
	}

	/**
	 * Validate
	 *
	 * @param mixed $value
	 * @return bool
	 * @throws Exception (on invalid pattern)
	 */
	public function validate($value): bool
	{
		$result = preg_match($this->pattern, (string)$value);

		if(preg_last_error() !== PREG_NO_ERROR)
		{
			throw new Exception('Invalid regex pattern "' . $this->pattern . '"');
		}

		return $result > 0;
	}
}