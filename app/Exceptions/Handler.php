<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e) {
            if ($e instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
                return response()->json(['message' => 'You do not have the required authorization.'],403);
            }
            else if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException && $request->wantsJson())
            {
                response()->json(['message' => 'Data not found'],404);
            }
            //return response()->json(['message' => $e->getMessage()], $e->getStatusCode() ?: 400);
        });
    }
}
