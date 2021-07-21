<?php

namespace Bulbadev\Autodoc\Elements;

class Rules
{

    protected bool    $isRequired = false;
    protected ?array  $in         = null;
    protected ?string $pattern    = null;

    public function getIn(): ?array
    {
        return $this->in;
    }

    public function setIn(array $in): self
    {
        $this->in = $in;

        return $this;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function setRequired(): self
    {
        $this->isRequired = true;

        return $this;
    }
}