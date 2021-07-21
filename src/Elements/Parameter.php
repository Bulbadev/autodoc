<?php

namespace Bulbadev\Autodoc\Elements;

use Bulbadev\Autodoc\Helpers\Helper;
use Illuminate\Support\Str;
use Minime\Annotations\Interfaces\AnnotationsBagInterface as AnnotationsBag;

class Parameter
{

    use Helper;

    public const IN_PATH      = 'path';
    public const IN_QUERY     = 'query';
    public const IN_COOKIE    = 'cookie';
    public const IN_HEADER    = 'header';
    public const IN           = [
        self::IN_PATH,
        self::IN_QUERY,
        self::IN_COOKIE,
        self::IN_HEADER,
    ];
    public const TYPE_INTEGER = 'integer';
    public const TYPE_NUMBER  = 'number';
    public const TYPE_STRING  = 'string';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPES        = [
        self::TYPE_INTEGER,
        self::TYPE_NUMBER,
        self::TYPE_STRING,
        self::TYPE_BOOLEAN,
    ];
    public Rules     $rules;
    protected        $value;
    protected string $name;
    protected string $realName;
    protected string $in;
    protected string $type        = self::TYPE_STRING;
    protected string $description = '';

    public function __construct(string $name, $value = null)
    {
        $this->name = $name;
        if (Str::contains($name, '.')) {
            $words          = explode('.', $name);
            $this->realName = array_shift($words);
            foreach ($words as $piece) {
                $this->realName .= '[' . $piece . ']';
            }
        }
        if ($value) {
            $this->type = $this->parseType($value);
        }
        $this->rules = new Rules();
    }

    public function buildFrom(AnnotationsBag $annotationsBag, $rules): self
    {
        $this->description = $annotationsBag->get($this->name, '');
        $this->parseRules($this->prepareRules($rules));

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIn(): string
    {
        return $this->in;
    }

    public function setIn(string $in): self
    {
        $this->in = $in;

        return $this;
    }

    public function getName(): string
    {
        return $this->realName ?? $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->rules->isRequired();
    }

    public function setRequired(): self
    {
        $this->rules->setRequired();

        return $this;
    }

    public function setRequiredIf($bool): self
    {
        if ($bool) {
            $this->setRequired();
        }

        return $this;
    }

    protected function parseRules(array $rules): void
    {
        foreach ($rules as $rule) {
            try {
                $rule = (string)$rule;
            } catch (\Throwable $e) {
                continue;
            }
            if (Str::contains($rule, ['required', 'requiredIf'])) {
                $this->setRequired();
            }
            if (Str::contains($rule, 'numeric')) {
                $this->setType(self::TYPE_NUMBER);
            }
            if (Str::contains($rule, 'integer')) {
                $this->setType(self::TYPE_INTEGER);
            }
            if (Str::contains($rule, 'boolean')) {
                $this->setType(self::TYPE_BOOLEAN);
            }
            if (Str::startsWith($rule, 'in:')) {
                $preparedString = strtolower(str_replace('"', '', Str::after($rule, 'in:')));
                $preparedArray  = array_unique(explode(',', $preparedString));
                $this->rules->setIn($preparedArray);
            }
            if (Str::startsWith($rule, 'regex:')) {
                Str::between($subject, $from, $to);
                $baseString     = Str::after($rule, 'regex:');
                $templateSymbol = substr($baseString, 0, 1);
                $trimmedString  = Str::between($baseString, $templateSymbol, $templateSymbol);
                $this->rules->setPattern($trimmedString);
            }
        }
    }

    protected function parseType($value): string
    {
        if ($this->isFloat($value)) {
            return self::TYPE_NUMBER;
        }
        if ($this->isInt($value)) {
            return self::TYPE_INTEGER;
        }
        if (\is_bool($value)) {
            return self::TYPE_BOOLEAN;
        }

        return self::TYPE_STRING;
    }

    protected function prepareRules($rules): array
    {
        if (\is_array($rules)) {
            return $rules;
        }
        if (\is_string($rules)) {
            return explode('|', $rules);
        }

        return [$rules];
    }
}