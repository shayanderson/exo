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

/**
 * Options singleton
 *
 * @author Shay Anderson
 *
 * @method get(string $key)
 * @method bool has(string $key)
 * @method void merge(\Exo\Options $options)
 * @method \Exo\Validator option(string $key)
 * @method required(array $keys)
 * @method set($key, $value = null)
 * @method array toArray()
 *
 * @method static get(string $key)
 * @method static has(string $key)
 * @method static merge(\Exo\Options $options)
 * @method static set($key, $value = null)
 * @method static toArray()
 */
abstract class Singleton extends \Exo\Factory\Singleton
{
	/**
	 * Options
	 *
	 * @var \Exo\Options
	 */
	private $options;

	/**
	 * Init
	 */
	final protected function __construct()
	{
		$this->options = new \Exo\Options;
		$this->__init();
	}

	/**
	 * Init subclass
	 */
	abstract protected function __init();

	/**
	 * Call methods
	 *
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 */
	public function __call(string $name, array $args)
	{
		return $this->getOptionsObject()->{$name}(...$args);
	}

	/**
	 * Call static methods
	 *
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 */
	public static function __callStatic(string $name, array $args)
	{
		return static::getInstance()->getOptionsObject()->{$name}(...$args);
	}

	/**
	 * Options object getter
	 *
	 * @return \Exo\Options
	 */
	public function getOptionsObject(): \Exo\Options
	{
		return $this->options;
	}

	/**
	 * Instances getter
	 *
	 * @staticvar array $instances
	 * @return array
	 */
	protected static function &instances(): array
	{
		static $instances = [];
		return $instances;
	}

	/**
	 * Options object getter
	 *
	 * @return \Exo\Options
	 */
	public static function object(): \Exo\Options
	{
		return static::getInstance()->getOptionsObject();
	}
}