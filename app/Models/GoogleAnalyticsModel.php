<?php

namespace App\Models;

use App\Services\GoogleAnalyticsService;
use CodeIgniter\Model;

class GoogleAnalyticsModel extends Model
{
    protected $gaService;

    public function __construct()
    {
        $this->gaService = new GoogleAnalyticsService();
    }

    public function getOverview(?string $startDate = null, ?string $endDate = null): array
    {
        $config = [
            'metrics' => [
                'totalUsers',
                'newUsers',
                'sessions',
                'screenPageViews',
                'bounceRate',
                'averageSessionDuration'
            ],
        ];

        if ($startDate && $endDate) {
            $config['date_ranges'] = [['start_date' => $startDate, 'end_date' => $endDate]];
        }

        $data = $this->gaService->runReport($config);

        // Map array ke format yang diinginkan
        return array_map(function ($item) {
            return [
                'totalUsers' => (int)($item['metrics'][0] ?? 0),
                'newUsers' => (int)($item['metrics'][1] ?? 0),
                'sessions' => (int)($item['metrics'][2] ?? 0),
                'screenPageViews' => (int)($item['metrics'][3] ?? 0),
                'bounceRate' => (float)($item['metrics'][4] ?? 0),
                'averageSessionDuration' => (float)($item['metrics'][5] ?? 0),
            ];
        }, $data);
    }

    public function getTopPages(int $limit = 10, ?string $startDate = null, ?string $endDate = null): array
    {
        $config = [
            'dimensions' => ['pageTitle', 'pagePath'],
            'metrics' => ['screenPageViews', 'totalUsers'],
            'order_bys' => [['metric' => 'screenPageViews', 'desc' => true]],
        ];

        if ($startDate && $endDate) {
            $config['date_ranges'] = [['start_date' => $startDate, 'end_date' => $endDate]];
        }

        $data = $this->gaService->runReport($config);

        // map array ke format yang diinginkan
        $mappedData = array_map(function ($item) {
            return [
                'pageTitle' => $item['dimensions'][0] ?? 'No Title',
                'pagePath' => $item['dimensions'][1] ?? '',
                'screenPageViews' => (int)($item['metrics'][0] ?? 0),
                'totalUsers' => (int)($item['metrics'][1] ?? 0),
            ];
        }, $data);

        // exclude if path contain /login atau /index.php atau halaman admin
        $filteredData = array_filter($mappedData, function ($item) {
            $excludedPaths = [
                '/login',
                '/index.php',
                '/admin',
                '/api',
            ];

            foreach ($excludedPaths as $excludedPath) {
                if (str_contains($item['pagePath'], $excludedPath)) {
                    return false;
                }
            }
            return true;
        });

        // only include path with /v1
        $filteredData = array_filter($filteredData, function ($item) {
            return str_starts_with($item['pagePath'], '/v1');
        });

        // Apply limit setelah filtering
        return array_slice($filteredData, 0, $limit);
    }

    public function getTrafficSources(?string $startDate = null, ?string $endDate = null): array
    {
        $config = [
            'dimensions' => ['sessionSource', 'sessionMedium'],
            'metrics' => ['sessions', 'screenPageViews', 'totalUsers'],
        ];

        if ($startDate && $endDate) {
            $config['date_ranges'] = [['start_date' => $startDate, 'end_date' => $endDate]];
        }

        $data = $this->gaService->runReport($config);

        // Map array ke format yang diinginkan
        return array_map(function ($item) {
            return [
                'sessionSource' => $item['dimensions'][0] ?? 'Unknown',
                'sessionMedium' => $item['dimensions'][1] ?? 'Unknown',
                'sessions' => (int)($item['metrics'][0] ?? 0),
                'screenPageViews' => (int)($item['metrics'][1] ?? 0),
                'totalUsers' => (int)($item['metrics'][2] ?? 0),
            ];
        }, $data);
    }

    public function getGeoData(?string $startDate = null, ?string $endDate = null): array
    {
        $config = [
            'dimensions' => ['country', 'region', 'city'],
            'metrics' => ['sessions', 'totalUsers'],
        ];

        if ($startDate && $endDate) {
            $config['date_ranges'] = [['start_date' => $startDate, 'end_date' => $endDate]];
        }

        $data = $this->gaService->runReport($config);

        // Map array ke format yang diinginkan
        return array_map(function ($item) {
            return [
                'country' => $item['dimensions'][0] ?? 'Unknown',
                'region' => $item['dimensions'][1] ?? 'Unknown',
                'city' => $item['dimensions'][2] ?? 'Unknown',
                'sessions' => (int)($item['metrics'][0] ?? 0),
                'totalUsers' => (int)($item['metrics'][1] ?? 0),
            ];
        }, $data);
    }

    public function getDeviceData(?string $startDate = null, ?string $endDate = null): array
    {
        $config = [
            'dimensions' => ['deviceCategory', 'operatingSystem', 'browser'],
            'metrics' => ['sessions', 'screenPageViews', 'totalUsers'],
        ];

        if ($startDate && $endDate) {
            $config['date_ranges'] = [['start_date' => $startDate, 'end_date' => $endDate]];
        }

        $data = $this->gaService->runReport($config);

        // Map array ke format yang diinginkan
        return array_map(function ($item) {
            return [
                'deviceCategory' => $item['dimensions'][0] ?? 'Unknown',
                'operatingSystem' => $item['dimensions'][1] ?? 'Unknown',
                'browser' => $item['dimensions'][2] ?? 'Unknown',
                'sessions' => (int)($item['metrics'][0] ?? 0),
                'screenPageViews' => (int)($item['metrics'][1] ?? 0),
                'totalUsers' => (int)($item['metrics'][2] ?? 0),
            ];
        }, $data);
    }

    public function getPopularPosts(?string $startDate = null, ?string $endDate = null): array
    {
        $config = [
            'dimensions' => ['pageTitle', 'pagePath'],
            'metrics' => ['screenPageViews'],
            'order_bys' => [['metric' => 'screenPageViews', 'desc' => true]],
        ];

        // Default to '2023-07-01' (all time) for "Popularity" if no dates provided
        $startDate = $startDate ?? '2023-07-01';
        $endDate = $endDate ?? 'today';

        $config['date_ranges'] = [['start_date' => $startDate, 'end_date' => $endDate]];

        $data = $this->gaService->runReport($config);

        // Map array ke format yang diinginkan
        $data = array_map(function ($item) {
            return [
                'title' => $item['dimensions'][0] ?? '',
                'path' => $item['dimensions'][1] ?? '',
                'views' => (int)($item['metrics'][0] ?? 0),
            ];
        }, $data);

        // Filter hanya post dengan path /v1/post/
        $filteredData = array_filter($data, function ($item) {
            return str_contains($item['path'], '/post/');
        });

        // Limit 5 data teratas
        return array_slice($filteredData, 0, 5);
    }


    public function getPostStats(?string $startDate = null, ?string $endDate = null): array
    {
        $dimension = $this->determineGranularity($startDate, $endDate);
        
        $config = [
            'dimensions' => [$dimension],
            'metrics' => ['screenPageViews'],
            'order_bys' => [
                ['dimension' => $dimension, 'desc' => false],
            ],
        ];

        if ($startDate && $endDate) {
            $config['date_ranges'] = [['start_date' => $startDate, 'end_date' => $endDate]];
        }

        $data = $this->gaService->runReport($config);

        return array_map(function ($item) use ($dimension) {
            return [
                'period' => $item['dimensions'][0] ?? 'Unknown',
                'screenPageViews' => (int)($item['metrics'][0] ?? 0),
                'granularity' => $dimension
            ];
        }, $data);
    }

    public function getUserStats(?string $startDate = null, ?string $endDate = null): array
    {
        $dimension = $this->determineGranularity($startDate, $endDate);

        $config = [
            'dimensions' => [$dimension],
            'metrics' => ['totalUsers'],
            'order_bys' => [
                ['dimension' => $dimension, 'desc' => false],
            ],
        ];

        if ($startDate && $endDate) {
            $config['date_ranges'] = [['start_date' => $startDate, 'end_date' => $endDate]];
        }

        $data = $this->gaService->runReport($config);

        return array_map(function ($item) use ($dimension) {
            return [
                'period' => $item['dimensions'][0] ?? 'Unknown',
                'totalUsers' => (int)($item['metrics'][0] ?? 0),
                'granularity' => $dimension
            ];
        }, $data);
    }

    private function determineGranularity(?string $startDate, ?string $endDate): string
    {
        if (!$startDate || $startDate === 'allTime' || $startDate === '2023-07-01') {
            return 'yearMonth';
        }

        // If preset ranges
        if (in_array($startDate, ['today', '7daysAgo', '30daysAgo', 'yesterday'])) {
            return 'date';
        }

        // Calculate days if custom date
        try {
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate === 'today' ? date('Y-m-d') : $endDate);
            $diff = $start->diff($end)->days;

            return ($diff <= 62) ? 'date' : 'yearMonth';
        } catch (\Exception $e) {
            return 'yearMonth';
        }
    }

    public function getMonthlyPostStats(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->getPostStats($startDate, $endDate);
    }

    public function getMonthlyUserStats(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->getUserStats($startDate, $endDate);
    }

    public function getViewsBySlug(array $slugs = [], ?string $startDate = null, ?string $endDate = null): array
    {
        if (empty($slugs)) return [];

        $startDate = $startDate ?? '2023-07-01';
        $endDate = $endDate ?? 'today';

        $response = $this->gaService->runReport([
            'dimensions' => ['pagePath'],
            'metrics' => ['screenPageViews'],
            'date_ranges' => [['start_date' => $startDate, 'end_date' => $endDate]],
        ]);

        $views = [];
        foreach ($response as $row) {
            $path = $row['dimensions'][0] ?? '';
            $count = $row['metrics'][0] ?? 0;

            foreach ($slugs as $slug) {
                if (str_contains($path, '/post/' . $slug)) {
                    $views[$slug] = (int)$count;
                }
            }
        }

        return $views;
    }
}
