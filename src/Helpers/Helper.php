<?php

namespace Bulbadev\Autodoc\Helpers;

trait Helper
{

    public function arrayFilterEmptyArrayRecursive(array $array): array
    {
        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                if (\count($value) === 0) {
                    unset($array[$key]);

                    continue;
                }
                $array[$key] = $this->arrayFilterNullRecursive($value);
            }
        }

        return $array;
    }

    public function arrayFilterNullRecursive(array $array): array
    {
        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                $array[$key] = $this->arrayFilterNullRecursive($value);
            }

            if (\is_null($value)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    public function isFloat($value): bool
    {
        if (\is_numeric($value)) {
            return \is_float($value + 0);
        }

        return false;
    }

    public function isInt($value): bool
    {
        if (\is_numeric($value)) {
            return \is_int($value + 0);
        }

        return false;
    }
}