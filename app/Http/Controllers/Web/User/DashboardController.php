<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Web\V1\Controller;
use App\Models\Announcement;
use App\Models\Emergency;
use App\Models\Maintenance;
use App\Models\Property;
use App\Models\User;

class DashboardController extends Controller
{



    public function index()
    {
        $propertyOwnerUser = User::where('role', 'property_owner')->count();
        $residentUser = User::where('role', 'resident')->count();
        $totalMaintenance = Maintenance::count();
        $totalProperties = Property::count();

        $emergencies = Emergency::with(['property', 'user', 'category'])->latest()->take(5)->get();
        $recentProperties = Property::with('user')->latest()->take(5)->get();
        $recentAnnouncements = Announcement::with('user')->latest()->take(5)->get();

        return view('backend.layouts.dashboard.index', compact(
            'propertyOwnerUser',
            'residentUser',
            'totalMaintenance',
            'totalProperties',
            'emergencies',
            'recentProperties',
            'recentAnnouncements'
        ));
    }
}
