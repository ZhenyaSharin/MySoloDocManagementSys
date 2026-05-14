<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPdf\Facades\Pdf;

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
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['auth', 'noadmin']);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');

Route::get('/pagenotfound', function () {
    return view('errors.404');
});

Route::get('/queryerror', function () {
    return view('errors.noquery');
})->name('noquery');

Auth::routes();

Route::get('/document-{id}', [App\Http\Controllers\DocumentPageController::class, 'index'])->name('docpage')->middleware('auth')->where('id', '[0-9]+');

Route::get('/assignment-{id}', [App\Http\Controllers\PageController::class, 'assignPage'])->name('assignpage')->middleware('auth')->where('id', '[0-9]+');

Route::get('/documents/makepdf', [App\Http\Controllers\DocumentPageController::class, 'pdf'])->name('docpdf')->middleware('auth');

Route::get('/documents', [App\Http\Controllers\DocumentPageController::class, 'list'])->name('docslist')->middleware('auth');

Route::get('/agreements', [App\Http\Controllers\PageController::class, 'agreementsList'])->name('agreementslist')->middleware('auth');

Route::get('/newagreements', [App\Http\Controllers\PageController::class, 'newAgreements'])->name('newagreementslist')->middleware('auth');

Route::get('/history', [App\Http\Controllers\PageController::class, 'agreementHistory'])->name('history')->middleware('auth');

// Route::get('/refresh', [App\Http\Controllers\AdminController::class, 'refresh'])->name('refresh')->middleware('auth');

Route::get('/check', [App\Http\Controllers\DocumentPageController::class, 'check'])->name('check')->middleware('auth');

Route::get('/assignments', [App\Http\Controllers\PageController::class, 'assignList'])->name('assignslist')->middleware('auth');

Route::get('/letters', [App\Http\Controllers\PageController::class, 'lettersList'])->name('letters')->middleware('auth');

Route::get('/outletters', [App\Http\Controllers\PageController::class, 'outlettersList'])->name('outletters')->middleware('auth');

Route::get('/contracts', [App\Http\Controllers\PageController::class, 'contractsList'])->name('contracts')->middleware('auth');

Route::get('/ordersod', [App\Http\Controllers\PageController::class, 'ordersODList'])->name('ordersod')->middleware('auth');

Route::get('/memos', [App\Http\Controllers\PageController::class, 'memosList'])->name('memos')->middleware('auth');

Route::get('/otherdocs', [App\Http\Controllers\PageController::class, 'otherDocsList'])->name('otherDocs')->middleware('auth');

Route::get('/notifications', [App\Http\Controllers\PageController::class, 'notificationsList'])->name('notifications')->middleware('auth');

Route::get('/ordocs', [App\Http\Controllers\PageController::class, 'orDocsList'])->name('ordocs')->middleware('auth');

Route::get('/additionalagreements', [App\Http\Controllers\PageController::class, 'additionalAgreementsList'])->name('addagreements')->middleware('auth');

Route::get('/socs', [App\Http\Controllers\PageController::class, 'socsList'])->name('socs')->middleware('auth');



Route::get('/blog', function () {
    return view('blog');
})->name('blog')->middleware('auth');

Route::get('/feedback', function () {
    return view('feedback');
})->name('feedback')->middleware('auth');

Route::get('/info', function () {
    return view('info');
})->name('info')->middleware('auth');
// Acquaintance

Route::get('/acquaintances', function () {
    return view('acquaintanceslist');
})->name('acquaintances')->middleware('auth');

Route::get('/analytics', [App\Http\Controllers\PageController::class, 'analyticsPage'])->name('analytics')->middleware('auth');

Route::get('/id_{login}', [App\Http\Controllers\PageController::class, 'userPage'])->name('account')->middleware('auth')->where('login', '[A-Za-z]+');

Route::get('/search', [App\Http\Controllers\PageController::class, 'searchPage'])->name('search')->middleware('auth');

Route::get('/archivedocs', [App\Http\Controllers\PageController::class, 'archivedocsList'])->name('archivedocs')->middleware('auth');

Route::get('/adminregister', [App\Http\Controllers\PageController::class, 'adminRegister'])->name('adminreg')->middleware('noadminreg');