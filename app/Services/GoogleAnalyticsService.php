<?php

namespace App\Services;

use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;

class GoogleAnalyticsService
{
    protected $client;
    protected $propertyId;

    public function __construct()
    {
        $this->client = GoogleClientService::getClient();
        $this->propertyId = GoogleClientService::getPropertyId();
    }

    public function runReport(array $config = []): array
    {
        // 1. Generate Cache Key
        $cacheKey = 'ga_report_' . md5(json_encode($config) . $this->propertyId);
        $cache = \Config\Services::cache();

        // 2. Check Circuit Breaker (if API failed recently, don't try again for 15 mins)
        if ($cache->get('ga_api_down')) {
            return [];
        }

        // 3. Return cached data if available (cache for 1 hour)
        if ($cachedData = $cache->get($cacheKey)) {
            return $cachedData;
        }

        try {
            $request = new RunReportRequest([
                'property' => 'properties/' . $this->propertyId,
                'date_ranges' => $this->buildDateRanges($config['date_ranges'] ?? []),
                'dimensions' => $this->buildDimensions($config['dimensions'] ?? []),
                'metrics' => $this->buildMetrics($config['metrics'] ?? []),
                'order_bys' => $this->buildOrderBys($config['order_bys'] ?? []),
            ]);

            $response = $this->client->runReport($request);
            $formattedData = $this->formatResponse($response);

            // 4. Cache successful response (1 hour)
            $cache->save($cacheKey, $formattedData, 3600);

            return $formattedData;

        } catch (\Exception $e) {
            log_message('error', '[GA Service] API Error: ' . $e->getMessage());

            // 5. Activate Circuit Breaker on quota/server errors (15 mins)
            if (strpos($e->getMessage(), 'RESOURCE_EXHAUSTED') !== false || strpos($e->getMessage(), 'quota') !== false) {
                $cache->save('ga_api_down', true, 900);
            }

            return []; // Graceful fallback
        }
    }

    protected function buildDateRanges(array $dateRanges): array
    {
        if (empty($dateRanges)) {
            $dateRanges = [[
                'start_date' => '2023-07-01', // Default start date for GA4
                'end_date' => 'today',
            ]];
        }

        $ranges = [];
        foreach ($dateRanges as $range) {
            $ranges[] = new DateRange($range);
        }
        return $ranges;
    }

    protected function buildDimensions(array $dimensions): array
    {
        $dimensionObjects = [];
        foreach ($dimensions as $dimension) {
            $dimensionObjects[] = new Dimension(['name' => $dimension]);
        }
        return $dimensionObjects;
    }

    protected function buildMetrics(array $metrics): array
    {
        $metricObjects = [];
        foreach ($metrics as $metric) {
            $metricObjects[] = new Metric(['name' => $metric]);
        }
        return $metricObjects;
    }

    protected function buildOrderBys(array $orderBys): array
    {
        $orderByObjects = [];
        foreach ($orderBys as $orderBy) {
            if (isset($orderBy['metric'])) {
                $orderByObjects[] = new OrderBy([
                    'metric' => new OrderBy\MetricOrderBy([
                        'metric_name' => $orderBy['metric']
                    ]),
                    'desc' => $orderBy['desc'] ?? true,
                ]);
            } elseif (isset($orderBy['dimension'])) {
                $orderByObjects[] = new OrderBy([
                    'dimension' => new OrderBy\DimensionOrderBy([
                        'dimension_name' => $orderBy['dimension']
                    ]),
                    'desc' => $orderBy['desc'] ?? true,
                ]);
            }
        }
        return $orderByObjects;
    }

    protected function formatResponse($response): array
    {
        $data = [];
        foreach ($response->getRows() as $row) {
            $item = [];

            // Process dimensions
            foreach ($row->getDimensionValues() as $index => $dimension) {
                $item['dimensions'][$index] = $dimension->getValue();
            }

            // Process metrics
            foreach ($row->getMetricValues() as $index => $metric) {
                $item['metrics'][$index] = $metric->getValue();
            }

            $data[] = $item;
        }

        return $data;
    }
}
