<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Liberu\Foundation\ApplicationCore\Http\Middleware\SecurityHeaders;
use Liberu\Foundation\Localization\Http\Middleware\SetLocale;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', [SetLocale::class, SecurityHeaders::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (ValidationException $exception, Request $request): ?JsonResponse {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'type' => 'https://httpstatuses.com/422',
                'title' => 'Validation failed',
                'status' => 422,
                'detail' => 'One or more request fields are invalid.',
                'instance' => $request->getRequestUri(),
                'errors' => $exception->errors(),
            ], 422);
        });

        $exceptions->render(function (Throwable $exception, Request $request): ?JsonResponse {
            if (! $request->is('api/*')) {
                return null;
            }

            $status = $exception instanceof AuthenticationException
                ? Response::HTTP_UNAUTHORIZED
                : ($exception instanceof HttpExceptionInterface
                    ? $exception->getStatusCode()
                    : Response::HTTP_INTERNAL_SERVER_ERROR);

            $title = Response::$statusTexts[$status] ?? 'Request failed';
            $detail = $status >= 500 ? 'An unexpected server error occurred.' : $exception->getMessage();

            return response()->json([
                'type' => 'https://httpstatuses.com/'.$status,
                'title' => $title,
                'status' => $status,
                'detail' => $detail !== '' ? $detail : 'The request could not be completed.',
                'instance' => $request->getRequestUri(),
            ], $status);
        });
    })->create();
