<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardReportController extends Controller
{
    public function index(): View
    {
        return view('dashboard.reports');
    }
}
