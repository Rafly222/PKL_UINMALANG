<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $activityFilter = $request->query('activity_filter', 'all');

        $baseQuery = ActivityLog::query();

        if ($startDate && $endDate) {
            $startUtc = Carbon::parse($startDate . ' 00:00:00', 'Asia/Jakarta')->setTimezone('UTC');
            $endUtc = Carbon::parse($endDate . ' 23:59:59', 'Asia/Jakarta')->setTimezone('UTC');
            $baseQuery->whereBetween('created_at', [$startUtc, $endUtc]);
        } elseif ($startDate) {
            $startUtc = Carbon::parse($startDate . ' 00:00:00', 'Asia/Jakarta')->setTimezone('UTC');
            $baseQuery->where('created_at', '>=', $startUtc);
        } elseif ($endDate) {
            $endUtc = Carbon::parse($endDate . ' 23:59:59', 'Asia/Jakarta')->setTimezone('UTC');
            $baseQuery->where('created_at', '<=', $endUtc);
        }

        $countTotalEvents = Event::count();
        $countLoginSuccess = (clone $baseQuery)->where('activity', 'login')->count();
        $countLoginFailed = (clone $baseQuery)->where('activity', 'login_failed')->count();
        $countBlocked = (clone $baseQuery)->whereIn('activity', ['blacklist_add', 'login_blocked'])->count();
        $countLogout = (clone $baseQuery)->where('activity', 'logout')->count();
        $countUniqueIps = (clone $baseQuery)->whereNotNull('ip_address')->where('ip_address', '!=', '')->distinct('ip_address')->count('ip_address');

        $logQuery = clone $baseQuery;
        if ($activityFilter && $activityFilter !== 'all') {
            if ($activityFilter === 'blocked') {
                $logQuery->whereIn('activity', ['blacklist_add', 'login_blocked']);
            } elseif ($activityFilter === 'auth') {
                $logQuery->whereIn('activity', ['login', 'login_failed', 'login_blocked', 'register', 'logout']);
            } elseif ($activityFilter === 'user') {
                $logQuery->whereIn('activity', ['create_user', 'update_user', 'delete_user', 'restore_user', 'approve_user', 'reject_user']);
            } elseif ($activityFilter === 'security') {
                $logQuery->whereIn('activity', ['blacklist_add', 'blacklist_remove', 'login_blocked']);
            } elseif ($activityFilter === 'event') {
                $logQuery->whereIn('activity', ['create_event', 'update_event', 'delete_event', 'submit_presence']);
            } else {
                $logQuery->where('activity', $activityFilter);
            }
        }

        $systemLogs = $logQuery->latest()->take(500)->get();

        return view('admin.logs', compact(
            'systemLogs',
            'countTotalEvents',
            'countLoginSuccess',
            'countLoginFailed',
            'countBlocked',
            'countLogout',
            'countUniqueIps',
            'startDate',
            'endDate',
            'activityFilter'
        ));
    }
}
