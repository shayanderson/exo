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

namespace Exo\Factory;

use Exo\Exception;

/**
 * Dynamic factory
 *
 * @author Shay Anderson
 */
class Dynamic
{
	/**
	 * Class name
	 *
	 * @var string
	 */
	private $class;

	/**
	 * Namespace
	 *
	 * @var string
	 */
	private $namespace;

	/**
	 * Reflection class object
	 *
	 * @var \ReflectionClass
	 */
	private $reflectionClass;

	/**
	 * Init
	 *
	 * @param string $class
	 * @param string $namespace
	 */
	public function __construct(string $class, string $namespace = null)
	{
		$this->class = $class;
		if($namespace)
		{
			$this->namespace = trim($namespace, '\\') . '\\';
		}
	}

	/**
	 * Class with namespace getter
	 *
	 * @return string
	 */
	public function getClass(): string
	{
		return "{$this->namespace}{$this->class}";
	}

	/**
	 * Instance getter for Singleton subclasses
	 *
	 * @return mixed
	 */
	public function getInstance()
	{
		return ($this->getClass())::getInstance();
	}

	/**
	 * Instance getter with optional constructor args
	 *
	 * @param mixed $args
	 * @return mixed
	 */
	public function newInstance(...$args)
	{
		return $this->getReflectionClassObject()->newInstanceArgs($args);
	}

	/**
	 * Instance getter with constructor args as array
	 *
	 * @param array $args
	 * @return mixed
	 */
	public function newInstanceArgs(array $args)
	{
		return $this->getReflectionClassObject()->newInstanceArgs($args);
	}

	/**
	 * ReflectionClass object getter
	 *
	 * @return \ReflectionClass
	 * @throws Exception (class does not exist)
	 */
	private function getReflectionClassObject(): \ReflectionClass
	{
		try
		{
			if(!$this->reflectionClass)
			{
				$this->reflectionClass = new \ReflectionClass($this->getClass());
			}

		}
		catch(\ReflectionException $ex)
		{
			throw new Exception($ex->getMessage(), [
				'class' => $this->getClass()
			]);
		}

		return $this->reflectionClass;
	}
}