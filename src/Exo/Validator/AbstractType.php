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

namespace Exo\Validator;

use Exo\Validator;

/**
 * Abstract validator type
 *
 * @author Shay Anderson
 */
abstract class AbstractType
{
	/**
	 * Exception class name
	 *
	 * @var string
	 */
	private static $assertionExceptionClass = Exception::class;

	/**
	 * Show value in exception message
	 *
	 * @var bool
	 */
	private static $assertionExceptionDisplayValue = false;

	/**
	 * Base message
	 *
	 * @var string
	 */
	private $baseMessage;

	/**
	 * Group message
	 *
	 * @var string
	 */
	private $groupMessage;

	/**
	 * Optional
	 *
	 * @var bool
	 */
	private $isOptional = false;

	/**
	 * Optional last
	 *
	 * @var bool
	 */
	private $isOptionalLast = false;

	/**
	 * Validation error messages
	 *
	 * @var array
	 */
	private $messages = [];

	/**
	 * Rules
	 *
	 * @var array
	 */
	private $rules = [];

	/**
	 * Validator object
	 *
	 * @var Validator
	 */
	private $validator;

	/**
	 * Init
	 *
	 * @param Validator $validator
	 */
	public function __construct(Validator &$validator)
	{
		$this->validator = &$validator;
	}

	/**
	 * Call method
	 *
	 * @param string $rule
	 * @param array $args
	 * @return \self
	 * @throws Exception (invalid rule)
	 */
	public function __call(string $rule, array $args): self
	{
		try
		{
			return $this->rule(
				(new \ReflectionClass(static::class . '\\' . ucfirst($rule)))
					->newInstanceArgs($args)
			);
		}
		catch(\ReflectionException $ex)
		{
			throw new Exception('Invalid rule "' . $rule . '" for type "' . static::class . '"');
		}
	}

	/**
	 * Clone method
	 */
	public function __clone()
	{
		$this->messages = []; // reset previous validation
	}

	/**
	 * Assert
	 *
	 * @param mixed $value
	 * @param callable $callback
	 * @return void
	 * @throws \Exception
	 */
	public function assert($value, callable $callback = null): void
	{
		$this->validate($value);

		if(count($this->messages))
		{
			if($callback)
			{
				if($callback($this->getMessages()) === true)
				{
					return; // halt
				}
			}

			$valString = null;
			if(self::$assertionExceptionDisplayValue)
			{
				if($value === null)
				{
					$valString = '[null]';
				}
				else if(is_bool($value))
				{
					$valString = $value === true ? '[true]' : '[false]';
				}
				else if(is_scalar($value))
				{
					if(is_string($value))
					{
						$valString = '"' . $value . '"';
					}
					else
					{
						$valString = $value;
					}
				}
			}

			throw new self::$assertionExceptionClass('Assertion failed:'
				. ( $this->validator->getName() ? ' "' . $this->validator->getName() . '"' : null )
				. ' ' . implode(', and ', $this->getMessages())
				. ( $valString ? ' (value: ' . $valString . ')' : null )
			);
		}
	}

	/**
	 * Message getter
	 *
	 * @return string|null
	 */
	public function getMessage(): ?string
	{
		$message = current($this->messages);
		return $message ? $message : null;
	}

	/**
	 * Messages getter
	 *
	 * @return array
	 */
	public function getMessages(): array
	{
		return $this->messages;
	}

	/**
	 * Group message setter
	 *
	 * @param string $message
	 */
	public function groupMessage(string $message)
	{
		$this->groupMessage = $message;
	}

	/**
	 * Check if optional match
	 */
	abstract protected static function isOptionalMatch($value): bool;

	/**
	 * Message setter
	 *
	 * @param string $message
	 */
	public function message(string $message)
	{
		if(!$this->isOptionalLast) // no message for optional
		{
			if(!count($this->rules)) // no rules, base message
			{
				$this->baseMessage = $message;
			}
			else if(end($this->rules))
			{
				end($this->rules)->setMessage($message);
			}
		}
	}

	/**
	 * Set as optional
	 */
	public function optional()
	{
		$this->isOptional = $this->isOptionalLast = true;
	}

	/**
	 * Add rule
	 *
	 * @param \Exo\Validator\RuleInterface $rule
	 * @return AbstractType
	 */
	public function rule(RuleInterface $rule): self
	{
		$this->rules[] = $rule;
		$this->isOptionalLast = false;
		return $this;
	}

	/**
	 * Custom assertion exception class setter
	 *
	 * @param string $class
	 * @return void
	 */
	final public static function setAssertionExceptionClass(string $class): void
	{
		self::$assertionExceptionClass = $class;
	}

	/**
	 * Display value in assertion exception message
	 *
	 * @param bool $display
	 * @return void
	 */
	final public static function setAssertionExceptionDisplayValue(bool $display): void
	{
		self::$assertionExceptionDisplayValue = $display;
	}

	/**
	 * Name setter
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName(string $name): void
	{
		$this->validator->setName($name);
	}

	/**
	 * Validate value
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function validate($value): bool
	{
		if(count($this->messages))
		{
			return false;
		}

		if($this->isOptional && static::isOptionalMatch($value)) // optional value
		{
			return true;
		}

		if(!static::validateBase($value)) // validate base type
		{
			$this->messages[] = $this->groupMessage ?: ( $this->baseMessage ?: static::BASE_MESSAGE );
		}

		foreach($this->rules as $rule)
		{
			if(!$rule->validate($value))
			{
				$this->messages[] = $this->groupMessage ?: $rule->getMessage();
			}

			if($this->messages)
			{
				$this->messages = array_unique($this->messages); // only allow unique messages
			}
		}

		return count($this->messages) === 0;
	}

	/**
	 * Validate base value
	 *
	 * @return bool
	 */
	abstract protected static function validateBase($value): bool;
}