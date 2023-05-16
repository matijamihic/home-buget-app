<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function report(Throwable $e)
    {
        if ($this->shouldReport($e) && app()->bound('sentry')) {
            app('sentry')->captureException($e);
        }

        parent::report($e);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Entry for '.str_replace('App', '', $exception->getModel()).' not found'], 404);
        }
    
        return parent::render($request, $exception);
    }
}
