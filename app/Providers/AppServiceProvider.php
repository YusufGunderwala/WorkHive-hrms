<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Share active announcements with the employee header component
        \Illuminate\Support\Facades\View::composer('components.employee-header', function ($view) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user && $user->role === 'employee') {
                $announcements = \App\Models\Announcement::active()
                    ->where(function ($query) use ($user) {
                        $query->where('department', 'All');
                        if ($user->employee && $user->employee->department) {
                            $query->orWhere('department', $user->employee->department);
                        }
                    })
                    ->latest()
                    ->get();
                $view->with('announcements', $announcements);
            }
        });
    }
}
