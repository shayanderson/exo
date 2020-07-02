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

use Exo\Validator\Exception;

/**
 * Validator
 *
 * @author Shay Anderson
 * #docs
 *
 * @method Validator alnum(bool $allowWhitespaces = false) must contain only alphanumeric characters
 * @method Validator alpha(bool $allowWhitespaces = false) must contain only alphabetic characters
 * @method Validator array(int $maxDepth = 1) must be an array with depth of $maxDepth
 * @method Validator between($min, $max) must be between both values
 * @method Validator bool() must be bool
 * @method Validator boolType() must be PHP primitive type bool
 * @method Validator contains($containsValue, bool $caseSensitive = true) must contain value
 * @method Validator digit() must be a digit
 * @method Validator email() must be a valid email address
 * @method Validator floatType() must be PHP primitive type float
 * @method Validator hash(string $knownHash) hashes must be equal
 * @method Validator in(...$list) must be in list
 * @method Validator integerType(...$list) must be PHP primitive type integer
 * @method Validator ipv4() must be valid IPv4 address
 * @method Validator ipv6() must be valid IPv6 address
 * @method Validator json() must be a valid JSON
 * @method Validator length(?int $min = null, ?int $max = null, ?int $exact = null) must be a specific length
 * @method Validator match($compareValue, bool $caseSensitive = true) values must be equal
 * @method Validator notEmpty() must not be empty
 * @method Validator notIn() must not be in list
 * @method Validator numeric() must be numeric
 * @method Validator password(string $hash) passwords must be equal
 * @method Validator regex(string $regex) must match regular expression
 * @method Validator required() value is required
 * @method Validator stdClass() must be instance of stdClass
 * @method Validator stringType() must be PHP primitive type string
 * @method Validator url() must be valid URL
 */
class Validator
{
	private $isOptional = false;
	private $isOptionalLast = false;
	private $messages = [];
	private $name;

	private static $ruleMap = [
		'array' => 'ArrayValue',
		'bool' => 'BoolValue'
	];

	private $rules = [];

	public function __construct(string $name = null)
	{
		if($name !== null)
		{
			$this->name = $name;
		}
	}

	public function __call(string $rule, array $args): self
	{
		$ruleName = $rule;

		try
		{
			if(isset(self::$ruleMap[$ruleName]))
			{
				$ruleName = self::$ruleMap[$ruleName];
			}

			return $this->rule((new \ReflectionClass('\Exo\Validator\Rule\\' . ucfirst($ruleName)))
				->newInstanceArgs($args));
		}
		catch(\ReflectionException $ex)
		{
			throw new Exception('Invalid rule "' . $ruleName . '"');
		}
	}

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

			throw new Exception('Assertion failed: '
				. ( $this->name !== null ? '"' . $this->name . '" ' : null )
				. implode(', ', $this->getMessages())
				. ( $value === null || is_scalar($value) ? ' (value: "' . $value . '")' : null ));
		}
	}

	public function getMessage(): ?string
	{
		$message = current($this->messages);
		return $message ? $message : null;
	}

	public function getMessages(): array
	{
		return $this->messages;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function message(string $message): self
	{
		// do not allow message for optional
		if(!$this->isOptionalLast && end($this->rules))
		{
			end($this->rules)->setMessage($message);
		}

		return $this;
	}

	public function optional(): self
	{
		$this->isOptional = $this->isOptionalLast = true;
		return $this;
	}

	public function rule(Validator\RuleInterface $rule): self
	{
		$this->rules[] = $rule;
		$this->isOptionalLast = false;
		return $this;
	}

	public function setName(string $name)
	{
		$this->name = $name;
	}

	public function validate($value): bool
	{
		if(count($this->messages))
		{
			return false;
		}

		// optional + undefined
		if($this->isOptional && ( $value === null || $value === '' ))
		{
			return true;
		}

		// apply required rule, only allow single use
		if(!$this->isOptional && !array_filter($this->rules, function($v){
			return $v instanceof Validator\Rule\Required;
		}))
		{
			$this->rules[] = new Validator\Rule\Required;
		}

		foreach($this->rules as $rule)
		{
			if(!$rule->validate($value))
			{
				$this->messages[] = $rule->getMessage();
			}
		}

		return count($this->messages) === 0;
	}
}