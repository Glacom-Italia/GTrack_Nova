<?php

use App\Models\Core\CoreBanner;
use App\Models\Core\CorePage;
use App\Models\Magazine\MagazineNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Card API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your card. These routes
| are loaded by the ServiceProvider of your card. You're free to add
| as many additional routes to this file as your card may require.
|
*/

Route::get('/what', function (Request $request) {
    return response()->json(['result' => true, 'data' => config('app.gtrack.what')]);
});

Route::get('/config', function (Request $request) {
    return response()->json(['result' => true, 'data' => config('app.gtrack')]);
});

Route::get('/of-what', function (Request $request) {
    if($request->resource_what === 'core_banner'){
        $result = CoreBanner::all();
    }else if($request->resource_what === 'core_page'){
        $result = CorePage::all();
    }else if($request->resource_what === 'magazine_news'){
        $result = MagazineNews::all();
    }else{
        $result = [];
    }

    return response()->json(['result' => true, 'data' => $result]);
});