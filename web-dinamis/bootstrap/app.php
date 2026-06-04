<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // 1. Daftarkan Alias Middleware Admin
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // 2. Tambahkan cek blokir di setiap request web (user yang sedang login)
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckUserBlocked::class);

        // 2. Atur Redirect Default
        // Jika user sudah login tapi mencoba akses halaman login/register,
        // mereka akan dilempar ke '/' (Halaman Utama Butik)
        $middleware->redirectTo(
            guests: '/login',
            users: '/'
        );

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();