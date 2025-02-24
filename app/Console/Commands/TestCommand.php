<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test test';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = "https://reqres.in/api/users/2";
        $response = Http::get($url);
       $jsonConvert = $response->body();
       $jsonConvert = json_decode($jsonConvert, true);
       dd($jsonConvert['data']['first_name']);
    }
}
