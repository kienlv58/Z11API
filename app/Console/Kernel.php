<?php

namespace App\Console;

use App\Category;
use App\Explain;
use App\Folder;
use App\Package;
use App\TextId;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {


        $schedule->call(function () {
            $arr_item_id = TextId::all();
            if(count($arr_item_id) >0){
                foreach($arr_item_id as $value){
                    if(Category::where('name_text_id',$value->text_id)->orWhere('describe_text_id',$value->text_id)->get()->first() != null)
                        continue;
                    if(Folder::where('name_text_id',$value->text_id)->orWhere('describe_text_id',$value->text_id)->get()->first() != null)
                        continue;
                    if(Package::where('name_text_id',$value->text_id)->orWhere('describe_text_id',$value->text_id)->get()->first() != null)
                        continue;
                    if(Explain::where('explain_text_id',$value->text_id)->get()->first() != null)
                        continue;
                    $value->delete();
                }
            }
        })->daily();

        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
