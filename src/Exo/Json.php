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

namespace Exo;

/**
 * JSON helper
 *
 * @author Shay Anderson
 */
class Json
{
	/**
	 * Decodes a JSON string
	 *
	 * @param string $json JSON string
	 * @param bool $assoc Returned types: false for objects, true for objects as associative arrays
	 * @param int $depth Recursion depth
	 * @param int $options JSON_BIGINT_AS_STRING, JSON_INVALID_UTF8_IGNORE,
	 *		JSON_INVALID_UTF8_SUBSTITUTE, JSON_OBJECT_AS_ARRAY, JSON_THROW_ON_ERROR
	 * @return mixed Null on decode failure
	 * @throws Exception on decode error
	 */
	public static function &decode(string $json, bool $assoc = false, int $depth = 512,
		int $options = 0)
	{
		$dec = json_decode($json, $assoc, $depth, $options);
		self::handleError(json_last_error(), 'decode');
		return $dec;
	}

	/**
	 * Returns the JSON rendering of a value
	 *
	 * @param mixed $value Value to encode; All strings must be UTF-8 encoded
	 * @param int $options JSON_FORCE_OBJECT, JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP,
	 *		JSON_HEX_APOS, JSON_INVALID_UTF8_IGNORE, JSON_INVALID_UTF8_SUBSTITUTE,
	 *		JSON_NUMERIC_CHECK, JSON_PARTIAL_OUTPUT_ON_ERROR, JSON_PRESERVE_ZERO_FRACTION,
	 *		JSON_PRETTY_PRINT, JSON_UNESCAPED_LINE_TERMINATORS, JSON_UNESCAPED_SLASHES,
	 *		JSON_UNESCAPED_UNICODE, JSON_THROW_ON_ERROR
	 * @param int $depth Recursion depth
	 * @return string|false False on encode failure
	 * @throws Exception on encode error
	 */
	public static function &encode($value, int $options = 0, int $depth = 512)
	{
		$enc = json_encode($value, $options, $depth);
		self::handleError(json_last_error(), 'encode');
		return $enc;
	}

	/**
	 * Handle a JSON error
	 *
	 * @param int $errorCode
	 * @param string $type
	 * @return void
	 * @throws Exception
	 */
	private static function handleError(int $errorCode, string $type): void
	{
		if($errorCode === JSON_ERROR_NONE)
		{
			return;
		}

		$error = json_last_error_msg();

		if($error !== false)
		{
			throw new Exception("JSON {$type}: {$error}", [
				'errorCode' => $errorCode
			]);
		}
	}
}