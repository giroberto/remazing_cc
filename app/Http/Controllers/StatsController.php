<?php

namespace App\Http\Controllers;

use App\models\Product;
use Illuminate\Http\Request;


class StatsController extends Controller
{


    /**
     * Retrieve all kpi data
     * @param Request $request
     * @return array
     */
    public function getKpiStats(Request $request)
    {
        $products = new Product();
        $stats = $products->getAllStats($request);
        $dataset = $this->buildDataset($stats);

        return $dataset;
    }

    /**
     * Format the output to a friendly chartjs format
     * @param $prices
     * @return array
     */
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

    /**
     * Generate a random html '#XXXXXX' color
     * @return string
     */
    private function generateHexColor()
    {
        return  "#" . substr(md5(rand()), 0, 6);
    }
}
