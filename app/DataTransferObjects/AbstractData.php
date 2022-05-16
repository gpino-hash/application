<?php


namespace App\DataTransferObjects;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

abstract class AbstractData extends Data
{

    public function __construct(private string $modelType)
    {
    }

    /**
     * @param Request $request
     * @return $this
     */
    abstract protected static function fromRequest(Request $request): self;

    /**
     * @param string ...$excludes
     * @return mixed
     */
    public function only(string ...$excludes): array
    {
        $data = array_filter(self::toArray());
        return Arr::only($data, $excludes);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getValue(string $key): mixed
    {
        return Arr::get($this->getAdditionalData(), $key);
    }
}