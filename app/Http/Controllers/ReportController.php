<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->isSuperAdmin(), 403);
        $period = in_array($request->query('period'), ['week', 'month', 'year'], true) ? $request->query('period') : 'month';
        $from = match ($period) { 'week' => now()->startOfWeek(), 'year' => now()->startOfYear(), default => now()->startOfMonth() };
        $base = DB::table('orders')->where('placed_at', '>=', $from)->whereNotIn('status', ['cancelled']);
        $countries = (clone $base)->select('destination_country', 'currency')->selectRaw('COUNT(*) AS order_count, SUM(total_minor) AS revenue_minor')
            ->groupBy('destination_country', 'currency')->orderByDesc('revenue_minor')->get();
        $channels = (clone $base)->join('channel_accounts', 'orders.channel_account_id', '=', 'channel_accounts.id')
            ->select('channel_accounts.platform', 'orders.currency')->selectRaw('COUNT(*) AS order_count, SUM(orders.total_minor) AS revenue_minor')
            ->groupBy('channel_accounts.platform', 'orders.currency')->orderByDesc('revenue_minor')->get();
        return view('reports.index', compact('period', 'countries', 'channels'));
    }
}
