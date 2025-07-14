<?php

namespace App\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Dashboard extends Component
{
    use WithPagination;

    #[Computed]
    public function totalUsers()
    {
        return User::count();
    }

    #[Computed]
    public function totalRoles()
    {
        return Role::count();
    }

    #[Computed]
    public function totalPermissions()
    {
        return Permission::count();
    }

    #[Computed]
    public function activeUsers()
    {
        return User::whereNotNull('email_verified_at')->count();
    }

    #[Computed]
    public function newUsersThisWeek()
    {
        return User::where('created_at', '>=', now()->subDays(7))->count();
    }

    #[Computed]
    public function recentUsers()
    {
        return User::latest()->paginate(5, pageName: 'recentUsersPage');
    }

    #[Computed]
    public function userRegistrationTrend()
    {
        $last7Days = [];
        $userCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateFormatted = $date->format('M j');

            $userCount = User::whereDate('created_at', $date->toDateString())->count();

            $last7Days[] = $dateFormatted;
            $userCounts[] = $userCount;
        }

        return [
            'labels' => $last7Days,
            'data' => $userCounts
        ];
    }

    #[Computed]
    public function totalActivities()
    {
        return Activity::count();
    }

    #[Computed]
    public function activitiesToday()
    {
        return Activity::whereDate('created_at', today())->count();
    }

    #[Computed]
    public function activitiesThisWeek()
    {
        return Activity::where('created_at', '>=', now()->subDays(7))->count();
    }

    #[Computed]
    public function recentActivities()
    {
        return Activity::with(['causer', 'subject'])
            ->latest()
            ->take(5)
            ->get();
    }

    #[Computed]
    public function activityTrend()
    {
        $last7Days = [];
        $activityCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateFormatted = $date->format('M j');

            $activityCount = Activity::whereDate('created_at', $date->toDateString())->count();

            $last7Days[] = $dateFormatted;
            $activityCounts[] = $activityCount;
        }

        return [
            'labels' => $last7Days,
            'data' => $activityCounts
        ];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
