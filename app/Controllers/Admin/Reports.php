<?php

namespace App\Controllers\Admin;

use App\Services\Analytics\AnalyticsService;

class Reports extends BaseController
{
    protected $analyticsService;

    public function __construct()
    {
        $this->analyticsService = new AnalyticsService();
    }

    public function index($year = null, $month = null)
    {
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');

        $year = ($year !== null) ? (int)$year : $currentYear;
        $month = ($month !== null) ? (int)$month : $currentMonth;

        $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);

        $postModel = new \App\Models\PostModel();
        
        // Determine the range of years available
        $minYearResult = $postModel->selectMin('YEAR(published_at)', 'min_year')->first();
        $minYear = (int)($minYearResult['min_year'] ?? $currentYear);
        if ($minYear < 2023) $minYear = 2023; // Project start floor

        $data['posts'] = $this->analyticsService->getPostsByMonthYear($monthStr, (string)$year);
        $data['year'] = $year;
        $data['month'] = $month;
        $data['minYear'] = $minYear;
        $data['maxYear'] = $currentYear;
        $data['currentYear'] = $currentYear;
        $data['currentMonth'] = $currentMonth;

        return $this->render('admin/reports/index', $data);
    }

    public function downloadPdf($year, $month)
    {
        try {
            $pdfContent = $this->analyticsService->generateMonthlyReportPdf($year, $month);
            $filename = 'Laporan-Berita-' . $year . '-' . $month . '.pdf';

            return $this->response->setHeader('Content-Type', 'application/pdf')
                ->setBody($pdfContent)
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghasilkan PDF: ' . $e->getMessage());
        }
    }
}
