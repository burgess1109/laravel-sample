<?php

use App\Http\Controllers\Auth\LoginAction;
use App\Http\Controllers\Auth\LogoutAction;
use App\Http\Controllers\Auth\RefreshAction;
use App\Http\Controllers\Roles\IndexAction as RolesIndexAction;
use App\Http\Controllers\Roles\ShowAction as RolesShowAction;
use App\Http\Controllers\Roles\StoreAction as RolesStoreAction;
use App\Http\Controllers\Roles\DestroyAction as RolesDestroyAction;
use App\Http\Controllers\Roles\UpdateAction as RolesUpdateAction;
use App\Http\Controllers\Users\IndexAction as UserIndexAction;
use App\Http\Controllers\Users\ShowAction as UserShowAction;
use App\Http\Controllers\Users\StoreAction as UserStoreAction;
use App\Http\Controllers\Users\DestroyAction as UserDestroyAction;
use App\Http\Controllers\Users\UpdateAction as UserUpdateAction;
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

Route::post('login', LoginAction::class);

Route::group([
    'middleware' => ['auth']
], function () {
    Route::post('logout', LogoutAction::class);
    Route::post('refresh', RefreshAction::class);

    Route::get('roles', RolesIndexAction::class);
    Route::get('roles/{id}', RolesShowAction::class);
    Route::post('roles', RolesStoreAction::class);
    Route::delete('roles/{id}', RolesDestroyAction::class);
    Route::patch('roles/{id}', RolesUpdateAction::class);

    Route::get('users', UserIndexAction::class);
    Route::get('users/{id}', UserShowAction::class);
    Route::post('users', UserStoreAction::class);
    Route::delete('users/{id}', UserDestroyAction::class);
    Route::patch('users/{id}', UserUpdateAction::class);
});
