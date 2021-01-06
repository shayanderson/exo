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

namespace Exo\Options;

use Exo\Validator;

/**
 * Options option
 *
 * @author Shay Anderson
 */
class Option
{
	/**
	 * Default value
	 *
	 * @var mixed
	 */
	private $default;

	/**
	 * Key
	 *
	 * @var string
	 */
	private $key;

	/**
	 * Convert type
	 *
	 * @var string
	 */
	private $type = 'auto';

	/**
	 * Validator object
	 *
	 * @var Validator
	 */
	private $validator;

	/**
	 * Init
	 *
	 * @param string $key
	 */
	public function __construct(string $key)
	{
		$this->key = $key;
	}

	/**
	 * Default value setter
	 *
	 * @param mixed $value
	 * @return \self
	 */
	public function default($value): self
	{
		$this->default = $value;
		return $this;
	}

	/**
	 * Get all as array
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			'default' => &$this->default,
			'type' => &$this->type,
			'validator' => &$this->validator
		];
	}

	/**
	 * Convert type setter
	 *
	 * @param string $type
	 * @return \self
	 */
	public function type(string $type): self
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * Validator object setter
	 *
	 * @return Validator
	 */
	public function &validator(): Validator
	{
		$this->validator = new Validator($this->key);
		return $this->validator;
	}
}