<?php

namespace App\Exceptions;

use App\PAM\Facades\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected function isApi(Request $request)
    {
        return $request->is('api*') && $request->acceptsJson();
    }


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
    }

    protected function prepareJsonResponse($request, Throwable $e)
    {
        return new JsonResponse(
            ApiResponse::withFailed()->withMessage($e->getMessage())
                ->withErrors($this->convertExceptionToArray($e)),
            $this->isHttpException($e) ? $e->getStatusCode() : 500,
            $this->isHttpException($e) ? $e->getHeaders() : [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $isAPI = collect($exception->guards())->contains(fn($val) => $val === 'api');

        if ($isAPI && $this->isApi($request)) {
            return response()
                ->json(
                    ApiResponse::withFailed()->withMessage($exception->getMessage())
                )
                ->setStatusCode(401);
        }

        return parent::unauthenticated($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($this->isApi($request)) {
            return response()->json(
                ApiResponse::withFailed()
                    ->withErrors($e->validator->errors()->toArray())
                    ->withMessage($e->getMessage())
            )->setStatusCode($e->status);
        }

        return parent::convertValidationExceptionToResponse($e, $request);
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (in_array($response->status(), [500, 503, 404, 403])) {
            return Inertia::render('Errors/Error', ['status' => $response->status()])
                ->toResponse($request)
                ->setStatusCode($response->status());
        } else if ($response->status() === 419) {
            return back()->with([
                'message' => __('The page expired, please try again.'),
            ]);
        }

        return $response;
    }
}
