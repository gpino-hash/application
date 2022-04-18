<?php

namespace App\Http\Builder;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Support\Arr;

abstract class Builder
{
    use HasAttributes;

    private array $data = [];

    protected string $class;

    public function __construct()
    {
        $this->initializeAttributes();
    }

    private function initializeAttributes(): void
    {
        foreach ($this->attributes as $attribute) {
            $this->$attribute = null;
        }
    }

    private function getValues(): void
    {
        foreach ($this->attributes as $key => $value) {
            $this->data[$value] = $this->{$value};
        }
    }

    public function build()
    {
        $this->getValues();
        return $this;
    }

    /**
     * @param $excludes
     * @return array
     */
    public function getAttributeArray($excludes): array
    {
        $data = [];
        foreach ($this->getAttributes() as $key) {
            $data[$key] = $this->{$key};
        }
        return Arr::except($data, $excludes);
    }
}
