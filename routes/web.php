<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('home.index', []);
// })->name('home.index');

// Route::get('/contact', function () {
//     return view('home.contact');
// })->name('home.contact');

// doing same as above, just simpler
Route::view('/', 'home.index')
    ->name('home.index');
Route::view('/contact', 'home.contact')
    ->name('home.contact');

$posts = [
    1 => [
        'title' => 'Intro to Laravel',
        'content' => 'This is a short intro to Laravel',
        'is_new' => true,
        'has_comments' => true,
    ],
    2 => [
        'title' => 'Intro to PHP',
        'content' => 'This is a short intro to PHP',
        'is_new' => false,
    ],
    3 => [
        'title' => 'Intro to C++',
        'content' => 'This is a short intro to C++',
        'is_new' => false,
    ]
];

Route::get('/posts', function () use ($posts) {
    // dd(request()->all());
    // dd((int) request()->input('page', 1));
    // dd((int) request()->query('page', 1));
    // compact($posts) === ['posts' => $posts]
    return view('posts.index', ['posts' => $posts]);
})->name('posts.index');

Route::get('/posts/{id}', function ($id) use ($posts) {

    abort_if(!isset($posts[$id]), 404);

    return view('posts.show', ['post' => $posts[$id]]);
})
    // Global constraint defined in RouteServiceprovider in boot method
    // constraint on route locally
    // ->where([
    //     'id' => '[0-9]+',
    // ])
    ->name('posts.show');

Route::prefix('/fun')->name('fun.')->group(function () use ($posts) {
    Route::get('/responses', function () use ($posts) {
        return response($posts, 201)
            ->header('Content-type', 'application-json')
            ->cookie('MY_COOKIE', 'Jakub Sredl', 3600);
    })->name('responses');

    Route::get('/redirect', function () {
        return redirect('/contact');
    })->name('redirect');

    Route::get('/back', function () {
        return back();
    })->name('back');

    Route::get('/named-route', function () {
        return redirect()->route('posts.show', ['id' => 1]);
    })->name('named-route');

    Route::get('/away', function () {
        return redirect()->away('https://google.com');
    })->name('away');

    Route::get('/json', function () use ($posts) {
        return response()->json($posts);
    })->name('json');

    Route::get('/download', function () use ($posts) {
        return response()->download(public_path('/daniel.jpg'), 'face.jpg');
    })->name('download');
});
