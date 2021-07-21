<?php

namespace Bulbadev\Autodoc\Elements;

use Illuminate\Support\Str;

class Tags
{

    protected array $tags = [];

    public function __construct(Uri $uri)
    {
        $keywords = explode('/', trim($uri->getUriTemplate(), '/'));

        foreach ($keywords as $word) {
            if (Str::contains($word, '{')) {
                continue;
            }

            $this->tags[] = $word;
            if (\count($this->tags) === config('autodoc.tags_count', 1)) {
                break;
            }
        }
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}