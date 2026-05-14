<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use \App\Exceptions\BusinessLogicException;
use \App\Exceptions\DatabaseException;
use \App\Exceptions\MailSettingException;
use Illuminate\Database\QueryException;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // if ($exception->errors() == 'Database connection [] not configured.') {
        //     return redirectTo(route('install'));
        // }
        if ($exception instanceof ValidationException) {
            if ($exception->errors() == '"email":["These credentials do not match our records."') {
                return redirectTo(route('login'));
            } else {
                // print_r($exception->errors());
                // return response()->json(["error" => "1", "error_message" => $exception->errors()]);
            }
        } elseif ($exception instanceof DatabaseException) {
            return response()->json(["error" => "2", "error_message" => $exception->errors()]);
        } elseif ($exception instanceof BusinessLogicException) {
            return response()->json(["error" => "3", "error_message" => $exception->errors()]);
        } elseif ($exception instanceof NotFoundHttpException) {
            return redirect('/pagenotfound');
        } elseif ($exception instanceof AuthenticationException) {
            return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
            // return redirect(route('login'));
        } elseif ($exception instanceof MailSettingException) {
            return response()->json(["error" => "5", "error_message" => $exception->errors()]);
        } elseif ($exception instanceof QueryException) {
            return redirect()->route('noquery');
        } else {
            return response()->json(["error" => "4", "error_message" => $exception->getMessage()]);
        }

        return parent::render($request, $exception);
    }

    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     return $request->expectsJson()
    //     ? response()->json(['message' => $exception->getMessage()], 401)
    //     : redirect()->guest($exception->redirectTo() ?? route('login'));
    // }
}
