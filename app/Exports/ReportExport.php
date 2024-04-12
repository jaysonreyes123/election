<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{
    public $headers;
    public $report_model;
    public $header_label;
    public function __construct($headers, $header_label, $report_model)
    {
        $this->headers = $headers;
        $this->report_model = $report_model;
        $this->header_label = $header_label;
    }
    public function view(): View
    {
        return view('content.admin.report.report', [
            'headers' => $this->headers,
            'header_label' => $this->header_label,
            'report_model' => $this->report_model
        ]);
    }
}
