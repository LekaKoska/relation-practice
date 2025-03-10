<?php

namespace App\Http\Controllers;

use App\Models\CitiesModel;
use App\Models\ForecastModel;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ForecastController extends Controller
{
    public function index(CitiesModel $city)
    {

       $cities = ForecastModel::where(['city_id' => $city->id])->get();


        $weatherService = new WeatherService();



         $jsonResponse = $weatherService->getAstronomy($city->name);

         $sunrise = $jsonResponse['astronomy']['astro']['sunrise'];
         $sunset =  $jsonResponse['astronomy']['astro']['sunset'];

        return view("weather.forecast", compact("cities", "sunrise", "sunset"));
    }

    public function search(Request $request)
    {
        $cityName = $request->get("city");

        Artisan::call("weather:get-real", ["city" => $cityName]);

        $cities = CitiesModel::with("todayForecast")->where("name", "LIKE", "%$cityName%")->get();
       if(count($cities) == 0)
       {
           return redirect()->back()->with("error", "Nismo uspeli da pronadjemo zeljeni grad!");
       }

        $userFavourite = [];
       if(Auth::check())
       {
           $userFavourite = Auth::user()->cityFavourites;
           $userFavourite = $userFavourite->pluck("city_id")->toArray();
       }


        return view("search_results", compact("cities", "userFavourite"));
    }


}
