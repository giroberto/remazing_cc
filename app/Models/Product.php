<?php

namespace App\models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Product extends Model
{
    public $timestamps = false;

    public function getPricesStats($query,$request)
    {
        $prices = $this->getMinAndMaxPrice();
        $prices->minprice = $request->get('minprice') ? $request->get('minprice') : $prices->minprice;
        $prices->maxprice = $request->get('maxprice') ? $request->get('maxprice') : $prices->maxprice;
        $priceRanges = range(
            floor($prices->minprice),
            ceil($prices->maxprice),
            round((ceil($prices->maxprice) - floor($prices->minprice)) / 5)
        );

        return array_map(function ($priceRange, $key) use ($query, $priceRanges) {
            if ($key < count($priceRanges) - 1)
                $query->selectRaw("count(*) filter( where price between $priceRange and " . $priceRanges[$key + 1] . " ) as \"price_\$$priceRange - $" . $priceRanges[$key + 1] . "\"");
        }, $priceRanges, array_keys($priceRanges));
    }

    public function getReviewsStats($query,$request)
    {
        $reviews = $this->getMinAndMaxReviews();
        $reviews->minreviews = $request->get('minreviews') ? $request->get('minreviews') : $reviews->minreviews;
        $reviews->maxreviews = $request->get('maxreviews') ? $request->get('maxreviews') : $reviews->maxreviews;
        $reviewRanges = range(
            $reviews->minreviews,
            $reviews->maxreviews,
            round(($reviews->maxreviews - $reviews->minreviews) / 5)
        );

        return array_map(function ($reviewRange, $key) use ($query, $reviewRanges) {
            if ($key < count($reviewRanges) - 1)
                $query->selectRaw("count(*) filter( where reviews between $reviewRange and " . $reviewRanges[$key + 1] . " ) as \"review_$reviewRange-" . $reviewRanges[$key + 1] . "\"");
        }, $reviewRanges, array_keys($reviewRanges));
    }

    public function getRatingsStats($query)
    {
        $query->selectRaw('count(*) filter( where avr_rating < 1 ) as "star_Below 1 star",
            count(*) filter( where avr_rating between 1 and 1.9 ) as "star_Between 1 and 2 stars",
            count(*) filter( where avr_rating between 2 and 2.9 ) as "star_Between 2 and 3 stars",
            count(*) filter( where avr_rating between 3 and 3.9 ) as "star_Between 3 and 4 stars",
            count(*) filter( where avr_rating >= 4) as "star_Above 4 stars"');
        return $query;
    }

    public function getFirstListedStats($query, $request)
    {
        $databaseDates = $this->getMinAndMaxFirstListedDate();

        $mindate = Carbon::createFromFormat('Y-m-d', $request->get('mindate')?  $request->get('mindate'): $databaseDates->mindate);
        $maxdate = Carbon::createFromFormat('Y-m-d', $request->get('maxdate')?  $request->get('maxdate'): $databaseDates->maxdate);
        if($mindate->diffInDays($maxdate) < 30){
            $period = CarbonPeriod::create($mindate, $maxdate);
            $dates = $period->toArray();
            array_map(function ($date) use ($query) {
                $query->selectRaw("count(*) filter( where first_listed = '".$date->format('Y-m-d')."' ) as \"date_". $date->format('Y-m-d')."\"");
            }, $dates);
            return $query;
        }
        elseif($mindate->diffInDays($maxdate) < 365){
            $period = CarbonPeriod::create($mindate, '1 month' , $maxdate);
            $dates = $period->toArray();
            array_map(function ($date) use ($query) {
                $query->selectRaw("count(*) filter( where first_listed between '".$date->format('Y-m-d')."' and '". $date->addMonth()->format('Y-m-d')  ."' ) as \"date_".$date->format('F/y')."\"");
            }, $dates);
            return $query;
        }
        else{
            $period = CarbonPeriod::create($mindate, '1 year', $maxdate);
            $dates = $period->toArray();
            array_map(function ($date) use ($query) {
                $query->selectRaw("count(*) filter( where first_listed between '".$date->format('Y-m-d')."' and '". $date->addYear()->format('Y-m-d')  ."' ) as \"date_".$date->format('F/y')."\"");
            }, $dates);
            return $query;
        }
    }

    public function filterResults($query, $request)
    {
        if ($request->get('minprice'))
            $query->where('price', '>=', $request->get('minprice'));
        if ($request->get('maxprice'))
            $query->where('price', '<', $request->get('maxprice'));
        if ($request->get('minreviews'))
            $query->where('reviews', '>=', $request->get('minreviews'));
        if ($request->get('maxreviews'))
            $query->where('reviews', '<', $request->get('maxreviews'));
        if ($request->get('minrating'))
            $query->where('avr_rating', '>=', $request->get('minrating'));
        if ($request->get('maxrating'))
            $query->where('avr_rating', '<', $request->get('maxrating'));
        if ($request->get('mindate'))
            $query->where('first_listed', '>=', $request->get('mindate'));
        if ($request->get('maxdate'))
            $query->where('first_listed', '<', $request->get('maxdate'));
        return $query;
    }

    public function getAllStats($request)
    {
        $query = $this->query();
        $this->getPricesStats($query, $request);
        $this->getReviewsStats($query, $request);
        $this->getRatingsStats($query);
        $this->getFirstListedStats($query, $request);
        $this->filterResults($query, $request);
        return $query->first()->toArray();
    }



    private static function getMinAndMaxPrice()
    {
        return DB::selectOne('select MIN(price) as minprice, MAX(price) as maxprice from products');
    }

    private static function getMinAndMaxReviews()
    {
        return DB::selectOne('select MIN(reviews) as minreviews, MAX(reviews) as maxreviews from products');
    }

    private static function getMinAndMaxFirstListedDate()
    {
        return DB::selectOne('select MIN(first_listed) as mindate, MAX(first_listed) as maxdate from products');
    }
}
