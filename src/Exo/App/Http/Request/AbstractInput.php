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

namespace Exo\App\Http\Request;

/**
 * Abstract request
 *
 * @author Shay Anderson
 */
abstract class AbstractInput
{
	/**
	 * Input type
	 */
	const TYPE = null;

	/**
	 * Default value
	 *
	 * @var mixed
	 */
	private $default;

	/**
	 * Input key
	 *
	 * @var string
	 */
	private $key;

	/**
	 * Validator
	 *
	 * @var \Exo\Validator\AbstractType
	 */
	private $validator;

	/**
	 * Init
	 *
	 * @param string $key
	 * @param mixed $default
	 */
	public function __construct(string $key, $default = null)
	{
		$this->key = $key;
		$this->default = $default;
	}

	/**
	 * Default value setter
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	private function default($value)
	{
		if(!$this->has() || $value === null || $value === '')
		{
			return $this->default;
		}

		return $value;
	}

	/**
	 * Value getter with sanitize filter for email
	 *
	 * @return mixed
	 */
	public function email()
	{
		return $this->filter(FILTER_SANITIZE_EMAIL);
	}

	/**
	 * Apply filter
	 *
	 * @param int $filter
	 * @return mixed
	 */
	private function filter(int $filter)
	{
		return $this->validate(
			$this->default(
				filter_input(static::TYPE, $this->key, $filter)
			)
		);
	}

	/**
	 * Value getter with sanitize filter for float
	 *
	 * @return mixed
	 */
	public function float()
	{
		return $this->filter(FILTER_SANITIZE_NUMBER_FLOAT);
	}

	/**
	 * Input array getter
	 *
	 * @return array
	 */
	abstract protected static function &getInputArray(): array;

	/**
	 * Check if key exists
	 *
	 * @return bool
	 */
	public function has(): bool
	{
		return isset(
			static::getInputArray()[$this->key]
		);
	}

	/**
	 * Value getter with sanitize filter for integer
	 *
	 * @return mixed
	 */
	public function integer()
	{
		return $this->filter(FILTER_SANITIZE_NUMBER_INT);
	}

	/**
	 * Value getter with sanitize filter for string
	 *
	 * @return type
	 */
	public function string()
	{
		return $this->filter(FILTER_SANITIZE_STRING);
	}

	/**
	 * Value getter with sanitize filter for URL
	 *
	 * @return type
	 */
	public function url()
	{
		return $this->filter(FILTER_SANITIZE_URL);
	}

	/**
	 * Validate value
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	private function validate($value)
	{
		if($this->validator)
		{
			$this->validator->assert($value);
		}

		return $value;
	}

	/**
	 * Validator setter
	 *
	 * @param \Exo\Validator\AbstractType $validator
	 * @return self
	 */
	public function &validator(\Exo\Validator\AbstractType $validator): self
	{
		$this->validator = $validator;
		$this->validator->setName(str_replace('\\', '.', static::class) . '.' . $this->key);
		return $this;
	}
}