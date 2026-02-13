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

    public function getOverviewData(): array
    {
        return $this->gaModel->getOverview();
    }

    public function getTopPagesData(int $limit = 10): array
    {
        return $this->gaModel->getTopPages($limit);
    }

    public function getTrafficSourcesData(): array
    {
        return $this->gaModel->getTrafficSources();
    }

    public function getGeoData(): array
    {
        return $this->gaModel->getGeoData();
    }

    public function getDeviceData(): array
    {
        return $this->gaModel->getDeviceData();
    }

    public function getPopularPostsData(): array
    {
        return $this->gaModel->getPopularPosts();
    }

    public function getMonthlyPostStatsData(): array
    {
        $data = $this->gaModel->getMonthlyPostStats();
        foreach ($data as &$item) {
            $item['formatted_date'] = format_date($item['year'] . '-' . $item['month'] . '-01', 'month_year');
        }
        return $data;
    }

    public function getMonthlyUserStatsData(): array
    {
        $data = $this->gaModel->getMonthlyUserStats();
        foreach ($data as &$item) {
            $item['formatted_date'] = format_date($item['year'] . '-' . $item['month'] . '-01', 'month_year');
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

        $html = view('Admin/Analytics/monthly_report_print', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }
}
