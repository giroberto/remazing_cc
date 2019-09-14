<?php

namespace Remazing_cc\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Remazing_cc\Http\Controllers\Controller;
use Remazing_cc\models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Input::all();
        $products = Product::query();
        //TODO shorten this
        if (Input::query('minPrice')) {
            $products->where('price', '>', Input::query('minPrice'));
        }
        if (Input::query('maxPrice')) {
            $products->where('price', '<', Input::query('maxPrice'));
        }
        if (Input::query('minReviews')) {
            $products->where('reviews_counter', '>', Input::query('minReviews'));
        }
        if (Input::query('maxReviews')) {
            $products->where('reviews_counter', '<', Input::query('maxReviews'));
        }
        if (Input::query('dateFirstListedMin')) {
            $products->where('first_listed', '>', Input::query('dateFirstListedMin'));
        }
        if (Input::query('dateFirstListedMax')) {
            $products->where('first_listed', '>', Input::query('dateFirstListedMax'));
        }
        return $products->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
