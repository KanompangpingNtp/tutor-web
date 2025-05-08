<?php

namespace App\Http\Controllers\admin\income_summary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncomeSummaryController extends Controller
{
    public function IncomeSummaryPage ()
    {
        return view('dashboard.admin.income_summary.page');
    }
}
