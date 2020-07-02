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

/**
 * Exo common functions
 *
 * @author Shay Anderson
 * #docs
 */

/**
 * Formatted string helper #todo mv to eco-app
 *
 * @param string $format
 * @param mixed $data
 * @param callable $callback
 * @return string
 * @throws \Exo\Exception (callback return void)
 */
function bind(string $format, $data, $callback = null): string
{
	$pattern = '/{\$([\w]+)}/i';

	if(( is_scalar($data) || $data === null ) && !is_callable($callback))
	{
		$params = func_get_args();
		unset($params[0]);
		return bind($format, $params);
	}

	if(is_object($data))
	{
		$data = (array)$data;
	}

	if(is_array($data))
	{
		// single dimension array
		if(count($data) === count($data, COUNT_RECURSIVE) && !is_object(current($data)))
		{
			if(is_callable($callback))
			{
				$data = (array)$callback((object)$data);
				if(!$data)
				{
					throw new Exception('Callback for bind() must return value');
				}
			}

			preg_replace_callback($pattern, function($m) use(&$format, &$data){
				if(( isset($data[$m[1]]) || array_key_exists($m[1], $data) )
					&& ( $data[$m[1]] === null || is_scalar($data[$m[1]]) ))
				{
					// replace placeholders with values
					$format = str_replace($m[0], $data[$m[1]], $format);
				}
			}, $format);
		}
		else // multidimensional array
		{
			$s = '';
			foreach($data as $a)
			{
				foreach($a as $k => $v)
				{
					if($v !== null && !is_scalar($v)) // depth not allowed, serialize
					{
						if(is_object($a))
						{
							$a->{$k} = serialize($a->{$k});
						}
						else
						{
							$a[$k] = serialize($a[$k]);
						}
					}
				}
				$s .= call_user_func_array(__FUNCTION__, [$format, $a, $callback]);
			}
			$format = &$s;
		}
	}

	return $format;
}

/**
 * Exo object getter
 *
 * @return \Exo\Factory
 */
#todo rm -- not needed, use at app level
//function exo(): \Exo\Factory
//{
//	return \Exo\Factory::getInstance();
//}

/**
 * Values printer
 *
 * @var mixed $values
 * @return void
 */
function pa(...$values): void
{
	foreach($values as $v)
	{
		echo is_scalar($v) || $v === null
			? $v . ( PHP_SAPI === 'cli' ? PHP_EOL : '<br />' )
			: ( PHP_SAPI === 'cli' ? print_r($v, true) : '<pre>' . print_r($v, true) . '</pre>' );
	}
}

/**
 * Global share helper
 *
 * @param string $key
 * @param mixed $value
 * @return mixed
 */
#todo rm - mv to eco-app
//function share(string $key = null, $value = null)
//{
//	if(func_num_args() === 1) // getter
//	{
//		return \Exo\Factory::getInstance()->share()->get($key);
//	}
//	else if(func_num_args() === 2) // setter
//	{
//		\Exo\Factory::getInstance()->share()->set($key, $value);
//	}
//
//	return \Exo\Factory::getInstance()->share();
//}