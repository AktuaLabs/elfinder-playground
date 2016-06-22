<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Aws\S3\S3Client;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/elfinder', function(){
    return response()->view('vendor.elfinder.elfinder');
});

Route::get('/aws', function(){

    $client = S3Client::factory([
        'credentials' => [
            'key'    => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ],
        'region' => env('AWS_REGION'),
        'version' => 'latest',
    ]);
    $adapter = new \League\Flysystem\AwsS3v3\AwsS3Adapter($client, env('AWS_CLOUD_BUCKET'), '', []);

    $s3 = new League\Flysystem\Filesystem($adapter);
    
    dd($s3->has('cloud'));

});

Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/pdf/download', 'PDFController@download');
Route::post('/api/post', function(\Illuminate\Http\Request $request){
    return response(env('APP_ENV') . ' - ' . env('DB_CONNECTION'));
})->name('api_env');
