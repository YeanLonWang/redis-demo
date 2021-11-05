<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
//        JsonResource::withoutWrapping();
        // 自定义 Blade 指令
        Blade::directive('formatTime', function ($time) {
            return "<?php echo date('Y-m-d H:i:s',$time)?>";
        });
    }
}
