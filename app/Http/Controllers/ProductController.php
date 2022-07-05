<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Pagination\ProductPagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {

        /*if (request()->user()->type !== UserType::USER) {
            $this->middleware(["auth:sanctum", 'abilities:' . implode(",", config("permission.all"))]);
        }*/
        try {
            return $this->sendResponse(new ProductCollection($this->paginate(resolve(ProductPagination::class))),
                __("pagination.success"));
        } catch (\Throwable $exception) {
            Log::stack(["stack"])->warning($exception->getMessage(), [$exception]);
            return $this->sendError(__("errors.error"),
                $this->isProduction() ? [] : [$exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if (\request()->user()->type !== UserType::USER) {
            $this->middleware(["auth:sanctum", 'abilities:' . implode(",", config("permission.all"))]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
