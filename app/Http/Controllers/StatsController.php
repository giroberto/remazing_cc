<?php

namespace App\Http\Controllers;

use App\models\Product;
use Illuminate\Http\Request;


class StatsController extends Controller
{
    public function getPriceKpi()
    {
        $products = new Product();
        $prices = $products->getPricesStats();
        $dataset = $this->buildDataset($prices);
        $dataset['label'] = 'Products price range';
        return [
            'labels' => array_keys((array) $prices),
            'datasets' => [$dataset]
        ];
    }

    public function getReviewKpi()
    {
        $products = new Product();
        $reviews = $products->getReviewsStats();
        $dataset = $this->buildDataset($reviews);
        $dataset['label'] = 'Products reviews';
        return [
            'labels' => array_keys((array) $reviews),
            'datasets' => [$dataset]
        ];
    }

    public function getRatingKpi()
    {
        $products = new Product();
        $ratings = $products->getRatingsStats();
        $dataset = $this->buildDataset($ratings);
        $dataset['label'] = 'Products rating';
        return [
            'labels' => array_keys((array) $ratings),
            'datasets' => [$dataset]
        ];
    }

    public function getDateKpi()
    {
        $days = [
            '2019-01-01',
            '2019-01-02',
            '2019-01-03',
            '2019-01-04',
            '2019-01-05',
            '2019-01-06',
            '2019-01-07',
            '2019-01-08',
            '2019-01-09',
            '2019-01-10',
            '2019-01-11',
            '2019-01-12',
            '2019-01-13',
            '2019-01-14',
            '2019-01-15'

        ];
        $products = new Product();
        $firstListedDates = $products->getDateListedStats($days);
        $dataset = $this->buildDataset($firstListedDates);
        $dataset['label'] = 'Products first listed date';
        return [
            'labels' => array_keys((array) $firstListedDates),
            'datasets' => [$dataset]
        ];
    }

    public function getKpiStats(Request $request)
    {
        $products = new Product();
        $stats = $products->getAllStats($request);
        $dataset = $this->buildDataset($stats);

        return $dataset;
    }

    private function buildDataset($prices)
    {
        $dataset = [];
        foreach ($prices as $categoryAndLabel => $value) {
            [$category, $label] = explode("_", $categoryAndLabel);
            $dataset[$category]['labels'][] = $label;
            $dataset[$category]['datasets'][0]['backgroundColor'][] = $this->generateHexColor();
            $dataset[$category]['datasets'][0]['data'][] = $value;
        }
        return $dataset;
    }

    private function generateHexColor()
    {
        return  "#" . substr(md5(rand()), 0, 6);
    }
}
