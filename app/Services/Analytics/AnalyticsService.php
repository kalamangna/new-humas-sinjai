<?php

namespace App\Services\Analytics;

use App\Services\BaseService;
use App\Models\GoogleAnalyticsModel;
use App\Models\PostModel;
use Dompdf\Dompdf;

class AnalyticsService extends BaseService
{
    protected $gaModel;
    protected $postModel;

    public function __construct()
    {
        $this->gaModel = new GoogleAnalyticsModel();
        $this->postModel = new PostModel();
    }

    public function getOverviewData(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->gaModel->getOverview($startDate, $endDate);
    }

    public function getTopPagesData(int $limit = 10, ?string $startDate = null, ?string $endDate = null): array
    {
        return $this->gaModel->getTopPages($limit, $startDate, $endDate);
    }

    public function getTrafficSourcesData(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->gaModel->getTrafficSources($startDate, $endDate);
    }

    public function getGeoData(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->gaModel->getGeoData($startDate, $endDate);
    }

    public function getDeviceData(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->gaModel->getDeviceData($startDate, $endDate);
    }

    public function getPopularPostsData(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->gaModel->getPopularPosts($startDate, $endDate);
    }

    public function getMonthlyPostStatsData(?string $startDate = null, ?string $endDate = null): array
    {
        $data = $this->gaModel->getMonthlyPostStats($startDate, $endDate);
        foreach ($data as &$item) {
            if ($item['granularity'] === 'yearMonth') {
                // Period is YYYYMM
                $year = substr($item['period'], 0, 4);
                $month = substr($item['period'], 4, 2);
                $item['formatted_date'] = format_date($year . '-' . $month . '-01', 'month_year');
            } else {
                // Period is YYYYMMDD
                $year = substr($item['period'], 0, 4);
                $month = substr($item['period'], 4, 2);
                $day = substr($item['period'], 6, 2);
                $item['formatted_date'] = date('d M', strtotime("$year-$month-$day"));
            }
        }
        return $data;
    }

    public function getMonthlyUserStatsData(?string $startDate = null, ?string $endDate = null): array
    {
        $data = $this->gaModel->getMonthlyUserStats($startDate, $endDate);
        foreach ($data as &$item) {
            if ($item['granularity'] === 'yearMonth') {
                $year = substr($item['period'], 0, 4);
                $month = substr($item['period'], 4, 2);
                $item['formatted_date'] = format_date($year . '-' . $month . '-01', 'month_year');
            } else {
                $year = substr($item['period'], 0, 4);
                $month = substr($item['period'], 4, 2);
                $day = substr($item['period'], 6, 2);
                $item['formatted_date'] = date('d M', strtotime("$year-$month-$day"));
            }
        }
        return $data;
    }

    public function getPostsByMonthYear(string $month, string $year): array
    {
        return $this->postModel->getPostsByMonthYear($month, $year);
    }

    public function generateMonthlyReportPdf(string $year, string $month): string
    {
        $posts = $this->getPostsByMonthYear($month, $year);
        
        $total_views = 0;
        foreach ($posts as $p) {
            $total_views += $p['views'];
        }

        $data = [
            'posts'       => $posts,
            'total_posts' => count($posts),
            'total_views' => $total_views,
            'year'        => $year,
            'month'       => $month
        ];

        $html = view('admin/reports/print', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }
}
