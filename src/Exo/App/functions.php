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

use Exo\Factory;
use Exo\System;

/**
 * Exo application helper functions
 *
 * @author Shay Anderson
 */

/**
 * Formatted string helper
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
 * Logger debug alias
 *
 * @param string $message
 * @param mixed $context
 * @return \Exo\Logger
 */
function debug($message = null, $context = null): \Exo\Logger
{
	return Factory::getInstance()->logger()->debug($message, $context, 2);
}

/**
 * Environment variables helper
 *
 * @param string $key
 * @param mixed $default
 * @param bool $invalidKeyException (throw exception on invalid key)
 * @return mixed
 * @throws \Exo\Exception (on invalid key)
 */
function env(string $key, $default = null, bool $invalidKeyException = false)
{
	return Factory::getInstance()->env()->get($key, $default, $invalidKeyException);
}

/**
 * Logger helper
 *
 * @param string $channel
 * @return \Exo\Logger
 */
function logger(string $channel = ''): \Exo\Logger
{
	return Factory::getInstance()->logger($channel);
}

/**
 * Values printer
 *
 * @var mixed $values
 * @return void
 */
function pa(...$values): void
{
	System::pa(...$values);
}

/**
 * Share getter/setter helper
 *
 * @param string $key (getter)
 * @param mixed $value (setter)
 * @return mixed
 */
function share(string $key, $value = null)
{
	if(func_num_args() === 1) // getter
	{
		return Factory::getInstance()->share()->get($key);
	}

	// setter
	Factory::getInstance()->share()->set($key, $value);
}

/**
 * Generate random token
 *
 * @param int $length (length returned in bytes, minimum 8)
 * @return string
 */
function token(int $length = 32): string
{
	if($length < 8)
	{
		throw new \Exo\Exception('Invalid length used in ' . __FUNCTION__
			. ', minimum length is 8');
	}

	if(!function_exists('random_bytes'))
	{
		return bin2hex(openssl_random_pseudo_bytes($length));
	}

	return bin2hex(random_bytes($length));
}