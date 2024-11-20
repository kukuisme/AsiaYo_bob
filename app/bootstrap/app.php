<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__)) 
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function(){
            Route::middleware('web')
            ->namespace('App\Http\Controllers')
            ->group(base_path('routes/web.php'));
    }
)


// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [ '*' ]); 
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
