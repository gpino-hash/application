<?php

namespace App\Http\Resources;

use App\Models\Currency;
use App\UseCase\Price\Price;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ProductCollection extends AbstractResourceCollection
{

    private array $data;

    /**
     * @return array
     */
    protected function getData(): array
    {

        foreach ($this->collection as $collection) {
            $this->data[] = [
                "id" => $collection->uuid,
                "title" => $collection->title,
                "description" => $collection->description,
                "amount" => $collection->stock,
                "price" => $this->buildPrices($collection->site->country->currencies, $collection->price, $collection->currency),
                "status" => $collection->status,
                "created_at" => Carbon::parse($collection->created_at)->format("d-m-Y"),
                "currency" => CurrencyCollection::collection($collection->currency),
                "site" => SiteCollection::collection($collection->site),
            ];
        }
        return $this->data;
    }

    /**
     * @param Collection $currencies
     * @param string $price
     * @param Currency $currency
     * @return array
     */
    private function buildPrices(Collection $currencies, string $price, Currency $currency): array
    {
        $prices = [];
        if (!empty($currencies)) {
            foreach ($currencies as $cu) {
                $prices[$cu->code] = resolve(Price::class)->apply($price, $currency, $cu);
            }
        }

        return $prices;
    }
}
