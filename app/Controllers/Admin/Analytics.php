<?php

namespace App\Controllers\Admin;

use App\Services\Analytics\AnalyticsService;

class Analytics extends BaseController
{
    protected $analyticsService;

    public function __construct()
    {
        $this->analyticsService = new AnalyticsService();
    }

    public function overviewView()
    {
        return $this->render('Admin/Analytics/overview');
    }

    public function topPagesView()
    {
        return $this->render('Admin/Analytics/top_pages');
    }

    public function trafficSourcesView()
    {
        return $this->render('Admin/Analytics/traffic_sources');
    }

    public function geoView()
    {
        return $this->render('Admin/Analytics/geo');
    }

    public function deviceCategoryView()
    {
        return $this->render('Admin/Analytics/device_category');
    }

    public function overview()
    {
        try {
            return $this->response->setJSON($this->analyticsService->getOverviewData());
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function topPages()
    {
        try {
            return $this->response->setJSON($this->analyticsService->getTopPagesData(10));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function trafficSources()
    {
        try {
            return $this->response->setJSON($this->analyticsService->getTrafficSourcesData());
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function geo()
    {
        try {
            return $this->response->setJSON($this->analyticsService->getGeoData());
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function deviceCategory()
    {
        try {
            return $this->response->setJSON($this->analyticsService->getDeviceData());
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function popularPosts()
    {
        try {
            return $this->response->setJSON($this->analyticsService->getPopularPostsData());
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function monthlyPostStats()
    {
        try {
            return $this->response->setJSON($this->analyticsService->getMonthlyPostStatsData());
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function monthlyUserStats()
    {
        try {
            return $this->response->setJSON($this->analyticsService->getMonthlyUserStatsData());
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function monthlyReport($year = null, $month = null)
    {
        if ($year === null || $month === null) {
            $year = date('Y');
            $month = date('m');
            return redirect()->to(base_url("admin/analytics/monthly-report/{$year}/{$month}"));
        }

        $data['posts'] = $this->analyticsService->getPostsByMonthYear($month, $year);
        $data['year'] = $year;
        $data['month'] = $month;
        $data['months'] = [];
        for ($m = 1; $m <= 12; $m++) {
            $data['months'][] = [
                'year' => $year,
                'month' => str_pad($m, 2, '0', STR_PAD_LEFT)
            ];
        }

        return $this->render('Admin/Analytics/monthly_report', $data);
    }

    public function downloadMonthlyReportPdf($year, $month)
    {
        try {
            $pdfContent = $this->analyticsService->generateMonthlyReportPdf($year, $month);
            $filename = 'Laporan-Bulanan-' . $year . '-' . $month . '.pdf';

            return $this->response->setHeader('Content-Type', 'application/pdf')
                ->setBody($pdfContent)
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghasilkan PDF: ' . $e->getMessage());
        }
    }

    protected function handleError(\Exception $e)
    {
        log_message('error', 'Google Analytics Error: ' . $e->getMessage());

        return $this->response->setJSON([
            'status' => 'error',
            'message' => trim(preg_replace('/\s+/', ' ', $e->getMessage())),
        ]);
    }
}