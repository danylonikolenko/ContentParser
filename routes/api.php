<?php

use App\Http\Controllers\ContentParserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/download_content', [ContentParserController::class , 'downloadContent'])->name('download.content');
Route::get('/get_content/{db}', [ContentParserController::class , 'getContent'])->name('get.content');
Route::post('/parse_content', [ContentParserController::class , 'parseContent'])->name('parse.content');
Route::post('/drop_db', [ContentParserController::class , 'dropDb'])->name('db.drop');
