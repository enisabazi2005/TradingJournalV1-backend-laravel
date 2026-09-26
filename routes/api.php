<?php

use App\Http\Controllers\Api\Internal\Mt5AccountSyncController;
use App\Http\Controllers\Api\Internal\Mt5SyncController as InternalMt5SyncController;
use App\Http\Controllers\Api\Internal\TradeScreenshotController as InternalTradeScreenshotController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\LiveController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\TradeJournalController;
use App\Http\Controllers\TradeScreenshotController;
use App\Http\Middleware\VerifyMt5SyncToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Trade Journal API
|--------------------------------------------------------------------------
*/

Route::get(
    '/journal',
    [JournalController::class, 'index']
);

Route::get(
    '/live',
    [LiveController::class, 'index']
);

Route::get(
    '/trades/{trade}',
    [TradeController::class, 'show']
);

Route::post(
    '/journal',
    [JournalController::class, 'store']
);

Route::put(
    '/journal/{entry}',
    [JournalController::class, 'update']
);

Route::put(
    '/trades/{trade}/journal',
    [TradeJournalController::class, 'update']
);


/*
|--------------------------------------------------------------------------
| Public Trade Screenshot API
|--------------------------------------------------------------------------
|
| Used by the frontend to display screenshots attached to trades.
|
*/

Route::get(
    '/trade-screenshots/{screenshot}',
    [TradeScreenshotController::class, 'show']
);


/*
|--------------------------------------------------------------------------
| Trade Screenshot Management
|--------------------------------------------------------------------------
|
| Used when manually adding/removing screenshots from a trade.
|
*/

Route::post(
    '/trades/{trade}/screenshots',
    [TradeScreenshotController::class, 'store']
);

Route::delete(
    '/trades/{trade}/screenshots/{screenshot}',
    [TradeScreenshotController::class, 'destroy']
);


Route::put(
    '/trades/{trade}/note',
    [InternalTradeScreenshotController::class, 'update']
);
 
Route::delete(
    '/trades/{trade}/note',
    [InternalTradeScreenshotController::class, 'destroy']
);
 


/*
|--------------------------------------------------------------------------
| Internal MT5 synchronization
|--------------------------------------------------------------------------
|
| These endpoints are ONLY for the local Python MT5 bridge.
|
| READ ONLY:
| Python reads MT5 data and sends it to Laravel.
| Laravel never sends trading commands to MT5.
|
*/

Route::middleware(VerifyMt5SyncToken::class)
    ->prefix('internal/mt5')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Account synchronization
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/account',
            [Mt5AccountSyncController::class, 'store']
        );


        /*
        |--------------------------------------------------------------------------
        | Trade synchronization
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/sync',
            [InternalMt5SyncController::class, 'store']
        );


        Route::get(
            '/trade-screenshot-status/{tradingAccountId}/{mt5PositionId}',
            [InternalTradeScreenshotController::class, 'status']
        );

        /*
        |--------------------------------------------------------------------------
        | Trade screenshot upload
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | The group already has /internal/mt5.
        | Therefore this route must only be /trade-screenshot.
        |
        */

        Route::post(
            '/trade-screenshot',
            [InternalTradeScreenshotController::class, 'store']
        );
    });