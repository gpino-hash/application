<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

abstract class AbstractJsonResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];
        foreach (parent::toArray($request) as $itemKey => $itemValue) {
            foreach (Arr::except($itemValue, $this->excludedAttribute()) as $key => $value) {
                if (!is_null($value)) {
                    $data[$itemKey][$this->reKeyAttribute($key)] = $value;
                }
            }
        }
        return $data;
    }

    /**
     * @return array
     */
    protected abstract function excludedAttribute(): array;

    /**
     * @return array
     */
    protected function keyChange(): array
    {
        return [];
    }

    /**
     * @param string $data
     * @return string
     */
    private function reKeyAttribute(string $data): string
    {
        if (!empty($this->keyChange()) && in_array($data, array_keys($this->keyChange()))) {
            return $this->keyChange()[$data];
        }

        return $data;
    }
}