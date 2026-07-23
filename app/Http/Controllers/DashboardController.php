<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Blacklist;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard Staff Pembuat Event
     */
    public function userIndex()
    {
        $events = Event::where('user_id', Auth::id())->latest()->get();
        return view('dashboard.user', compact('events'));
    }

    /**
     * Dashboard Super Admin
     */
    public function adminIndex()
    {
        $events = Event::with('creator')->latest()->get();
        $totalUsers = User::where('status', 'approved')->count();
        $totalBlacklists = Blacklist::count();
        $totalLogs = ActivityLog::count();
        $pendingUsersCount = User::where('status', 'pending')->count();

        return view('dashboard.admin', compact('events', 'totalUsers', 'totalBlacklists', 'totalLogs', 'pendingUsersCount'));
    }
}
