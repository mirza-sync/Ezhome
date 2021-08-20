<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Arr;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // if statements added for multiuser redirects if there's exception
        if($exception instanceof AuthenticationException) {
            $guard = Arr::get($exception->guards(), 0);
            switch($guard) {
                case 'landlord':
                    // dd($exception->getMessage());
                    return redirect(route('landlord.login'));
                    break;
                case 'admin':
                    // dd($exception->getMessage());
                    return redirect(route('admin.login'));
                    break;
                default:
                    return redirect(route('login'));
                    break;
            }
        }

        return parent::render($request, $exception);
    }
}
