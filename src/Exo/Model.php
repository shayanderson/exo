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

namespace Exo;

/**
 * Model
 *
 * @author Shay Anderson
 */
abstract class Model
{
	/**
	 * Entity class name
	 */
	const ENTITY = null;

	/**
	 * Entity factory
	 *
	 * @param array|object $data
	 * @return \Exo\Entity
	 */
	final public function entity($data = null): \Exo\Entity
	{
		$class = static::ENTITY;

		if(!$class)
		{
			throw new Exception('Model class ' . static::class . ' must have constant ENTITY set'
				. ' before using the entity() method');
		}

		if(is_object($data))
		{
			$data = (array)$data;
		}

		return new $class($data);
	}

	/**
	 * Array of entities factory
	 *
	 * @param array $data
	 * @param array $filter
	 * @param bool $voidable
	 * @return array (of \Exo\Entity)
	 */
	final public function &entityArray(array $data, array $filter = null,
		bool $voidable = false): array
	{
		foreach($data as $k => $v)
		{
			$data[$k] = $this->entity($v)->toArray($filter, $voidable);
		}

		return $data;
	}
}