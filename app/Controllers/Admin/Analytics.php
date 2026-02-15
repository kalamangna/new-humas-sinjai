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
        return $this->render('admin/analytics/overview');
    }

    public function topPagesView()
    {
        return $this->render('admin/analytics/top_pages');
    }

    public function trafficSourcesView()
    {
        return $this->render('admin/analytics/traffic_sources');
    }

    public function geoView()
    {
        return $this->render('admin/analytics/geo');
    }

    public function deviceCategoryView()
    {
        return $this->render('admin/analytics/device_category');
    }

    public function overview()
    {
        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            return $this->response->setJSON($this->analyticsService->getOverviewData($startDate, $endDate));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function topPages()
    {
        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            return $this->response->setJSON($this->analyticsService->getTopPagesData(10, $startDate, $endDate));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function trafficSources()
    {
        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            return $this->response->setJSON($this->analyticsService->getTrafficSourcesData($startDate, $endDate));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function geo()
    {
        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            return $this->response->setJSON($this->analyticsService->getGeoData($startDate, $endDate));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function deviceCategory()
    {
        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            return $this->response->setJSON($this->analyticsService->getDeviceData($startDate, $endDate));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function popularPosts()
    {
        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            return $this->response->setJSON($this->analyticsService->getPopularPostsData($startDate, $endDate));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function monthlyPostStats()
    {
        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            return $this->response->setJSON($this->analyticsService->getMonthlyPostStatsData($startDate, $endDate));
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function monthlyUserStats()
    {
        try {
            $startDate = $this->request->getGet('start_date');
            $endDate = $this->request->getGet('end_date');
            return $this->response->setJSON($this->analyticsService->getMonthlyUserStatsData($startDate, $endDate));
        } catch (\Exception $e) {
            return $this->handleError($e);
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