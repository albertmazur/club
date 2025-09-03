<?php

use App\Enums\Language;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StadiumController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\PasswordChangeController;

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

Route::get('/', [EventController::class, 'welcome'])->name('home');
Route::get('/about',   [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');
Route::get('/lang/{language}', [HomeController::class, 'changeLanguage'])->whereIn('language', array_map(fn($c) => $c->value, Language::cases()))->name('lang.switch');

Route::group([
    'prefix' => 'event',
    'namespace' => 'event',
    'as' => 'event.'
], function (){
    Route::get('index', [EventController::class, 'index'])->name('index');
    Route::get('show/{eventId}', [EventController::class, 'show'])->whereNumber('eventId')->name('show');
});

Route::group([
    'prefix' => 'submission',
    'namespace' => 'submission',
    'as' => 'submission.'
], function (){
    Route::post('store', [SubmissionController::class, 'store'])->name('store');
});

Route::group([
    'prefix' => 'stadium',
    'namespace' => 'stadium',
    'as' => 'stadium.'
], function (){
    Route::get('list', [StadiumController::class, 'list'])->name('list');
});

Route::middleware(['auth', 'verified', 'force_password_change'])->group(function (){
    Route::group([
        'prefix' => 'event',
        'namespace' => 'event',
        'as' => 'event.'
    ], function (){
        Route::get('dashboard', [EventController::class, 'dashboard'])->name('dashboard');
        Route::get('edit/{eventId}', [EventController::class, 'edit'])->name('edit')->whereNumber('eventId');
        Route::post('store', [EventController::class, 'store'])->name('store');
        Route::put('update', [EventController::class, 'update'])->name('update');
    });

    Route::group([
        'prefix' => 'submission',
        'namespace' => 'submission',
        'as' => 'submission.'
    ], function (){
        Route::get('index', [SubmissionController::class, 'index'])->name('index');
        Route::delete('remove', [SubmissionController::class, 'destroy'])->name('remove');
    });

    Route::group([
        'prefix' => 'ticket',
        'namespace' => 'ticket',
        'as' => 'ticket.'
    ], function (){
        Route::get('index', [TicketController::class, 'index'])->name('index');
        Route::post('store', [TicketController::class, 'store'])->name('store');
        Route::get('history', [TicketController::class, 'history'])->name('history');
        Route::put('back', [TicketController::class, 'backTicket'])->name('back');
        Route::get('payment-status', [TicketController::class, 'paymentStatus'])->name('payment.status');
        Route::get('scanner', [TicketController::class, 'scanner'])->name('scanner');
        Route::post('verify', [TicketController::class, 'verifyQr'])->name('verify');
    });

    Route::group([
        'prefix' => 'stadium',
        'namespace' => 'stadium',
        'as' => 'stadium.'
    ], function (){
        Route::get('index', [StadiumController::class, 'index'])->name('index');
        Route::post('store', [StadiumController::class, 'store'])->name('store');
        Route::get('{stadiumId}', [StadiumController::class, 'edit'])->whereNumber('stadiumId')->name('edit');
        Route::put('update', [StadiumController::class, 'update'])->name('update');
    });

    Route::group([
        'prefix' => 'comment',
        'namespace' => 'comment',
        'as' => 'comment.'
    ], function (){
        Route::get('index', [CommentController::class, 'index'])->name('index');
        Route::post('store', [CommentController::class, 'store'])->name('store');
        Route::delete('remove', [CommentController::class, 'destroy'])->name('remove');
        Route::get('my', [CommentController::class, 'myComments'])->name('my');
    });

    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::group([
        'prefix' => 'user',
        'namespace' => 'user',
        'as' => 'user.'
    ], function (){
        // Admin
        Route::get('users', [UserController::class, 'index'])->name('users');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::get('edit/{userId}', [UserController::class, 'edit'])->whereNumber('userId')->name('edit');
        Route::put('update', [UserController::class, 'update'])->name('update');
        Route::delete('delete', [UserController::class, 'delete'])->name('delete');
        // Setting
        Route::get('settings', [UserController::class, 'editProfile'])->name('settings');
        Route::post('settings/profile', [UserController::class, 'updateProfile'])->name('update.profile');
        Route::post('settings/email', [UserController::class, 'changeEmail'])->name('change.email');
        Route::post('settings/password', [UserController::class, 'changePassword'])->name('change.password');
        Route::delete('settings/account', [UserController::class, 'deleteAccount'])->name('delete.account');
    });
});

Auth::routes(['verify' => true]);
Route::middleware(['auth'])->group(function (){
    Route::get('/password/change', [PasswordChangeController::class, 'showForm'])->name('password.change.form');
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.update');
});