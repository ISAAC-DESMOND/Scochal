<?php

namespace App\Providers;

use App\Models\member_joins;
use App\Models\team;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

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
        Blade::if('created', function ($user_id) {
            return team::where('user_id', Auth::id())->exists();
        });

        Blade::if('team_manager', function ($team_id) {
            $team = team::find($team_id);
            return $team && $team->user_id == Auth::id();
        });
        
        Blade::if('team_member', function ($team_id) {
            return member_joins::where('team_id',$team_id)->
		where('user_id',Auth::id())->
		where('status','member')->exists();
        });
    }
}

?>
