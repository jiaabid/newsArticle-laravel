<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;

// use App\Http\Controllers\API\ArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
  
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
     
Route::middleware('auth:api')->group( function () {
    Route::get('/hello',function(){
        return "hello world";
    });
    Route::get("article",[ArticleController::class,'newsapiArticles']);
    Route::get("ny/article",[ArticleController::class,'nytimesArticles']);
    Route::post("preferences",[UserController::class,'savePreferences']);
    Route::get("personalFeed",[ArticleController::class,'personalFeed']);
    
    // Route::resource('products', ProductController::class);
});

/* Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\AuthController@register');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */