<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
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
            $this->logException($e);
        });

        $this->renderable(function (Throwable $e, Request $request) {
            return $this->handleException($e, $request);
        });
    }

    /**
     * Handle different types of exceptions
     */
    protected function handleException(Throwable $e, Request $request)
    {
        // AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return $this->handleAjaxException($e, $request);
        }

        // Web requests
        return $this->handleWebException($e, $request);
    }

    /**
     * Handle AJAX exceptions
     */
    protected function handleAjaxException(Throwable $e, Request $request)
    {
        $statusCode = 500;
        $message = 'Bir hata oluştu. Lütfen tekrar deneyin.';

        if ($e instanceof ValidationException) {
            $statusCode = 422;
            $message = 'Doğrulama hatası';
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $e->errors(),
                'error_type' => 'validation'
            ], $statusCode);
        }

        if ($e instanceof ModelNotFoundException) {
            $statusCode = 404;
            $message = 'Kayıt bulunamadı.';
        }

        if ($e instanceof QueryException) {
            $statusCode = 500;
            $message = 'Veritabanı hatası oluştu.';
            
            // Log the SQL error for debugging
            Log::error('SQL Error', [
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'url' => $request->fullUrl(),
            ]);
        }

        if ($e instanceof TokenMismatchException) {
            $statusCode = 419;
            $message = 'CSRF token hatası. Sayfayı yenileyin.';
        }

        if ($e instanceof AuthenticationException) {
            $statusCode = 401;
            $message = 'Oturum süreniz dolmuş. Lütfen tekrar giriş yapın.';
        }

        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $message = $this->getHttpErrorMessage($statusCode);
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'error_type' => $this->getErrorType($e),
            'debug_info' => config('app.debug') ? [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ] : null
        ], $statusCode);
    }

    /**
     * Handle web exceptions
     */
    protected function handleWebException(Throwable $e, Request $request)
    {
        if ($e instanceof ValidationException) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        if ($e instanceof ModelNotFoundException) {
            toastr()->error('Kayıt bulunamadı.');
            return redirect()->back();
        }

        if ($e instanceof QueryException) {
            Log::error('SQL Error', [
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'url' => $request->fullUrl(),
            ]);

            toastr()->error('Veritabanı hatası oluştu. Lütfen tekrar deneyin.');
            return redirect()->back();
        }

        if ($e instanceof TokenMismatchException) {
            toastr()->error('CSRF token hatası. Sayfayı yenileyin.');
            return redirect()->back();
        }

        if ($e instanceof AuthenticationException) {
            toastr()->error('Oturum süreniz dolmuş. Lütfen tekrar giriş yapın.');
            return redirect()->route('login');
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        // Default error handling
        if (config('app.debug')) {
            return parent::render($request, $e);
        }

        toastr()->error('Beklenmeyen bir hata oluştu. Lütfen tekrar deneyin.');
        return redirect()->back();
    }

    /**
     * Log exception with context
     */
    protected function logException(Throwable $e)
    {
        $context = [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_data' => $this->sanitizeRequestData(request()->all()),
        ];

        // Don't log validation exceptions
        if ($e instanceof ValidationException) {
            return;
        }

        // Log with appropriate level
        if ($e instanceof QueryException) {
            Log::error('Database Error: ' . $e->getMessage(), $context);
        } elseif ($e instanceof AuthenticationException) {
            Log::warning('Authentication Error: ' . $e->getMessage(), $context);
        } elseif ($e instanceof ModelNotFoundException) {
            Log::info('Model Not Found: ' . $e->getMessage(), $context);
        } else {
            Log::error('Application Error: ' . $e->getMessage(), $context);
        }

        // Send email for critical errors in production
        if (config('app.env') === 'production' && $this->isCriticalError($e)) {
            $this->sendErrorNotification($e, $context);
        }
    }

    /**
     * Check if error is critical
     */
    protected function isCriticalError(Throwable $e): bool
    {
        $criticalErrors = [
            QueryException::class,
            \PDOException::class,
            \ErrorException::class,
        ];

        foreach ($criticalErrors as $criticalError) {
            if ($e instanceof $criticalError) {
                return true;
            }
        }

        return false;
    }

    /**
     * Send error notification email
     */
    protected function sendErrorNotification(Throwable $e, array $context)
    {
        try {
            Mail::send('emails.error-notification', [
                'error' => $e,
                'context' => $context,
                'timestamp' => now(),
            ], function ($message) {
                $message->to(config('mail.admin_email', 'admin@example.com'))
                    ->subject('Kritik Sistem Hatası - Kalefrit Barkod Sistemi');
            });
        } catch (\Exception $mailException) {
            Log::error('Failed to send error notification email', [
                'original_error' => $e->getMessage(),
                'mail_error' => $mailException->getMessage(),
            ]);
        }
    }

    /**
     * Sanitize request data for logging
     */
    protected function sanitizeRequestData(array $data): array
    {
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'api_key'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '***HIDDEN***';
            }
        }

        return $data;
    }

    /**
     * Get HTTP error message
     */
    protected function getHttpErrorMessage(int $statusCode): string
    {
        $messages = [
            400 => 'Geçersiz istek.',
            401 => 'Yetkilendirme gerekli.',
            403 => 'Bu işlemi yapmak için yetkiniz yok.',
            404 => 'Sayfa bulunamadı.',
            405 => 'Bu HTTP metodu desteklenmiyor.',
            419 => 'CSRF token hatası.',
            422 => 'Doğrulama hatası.',
            429 => 'Çok fazla istek gönderildi.',
            500 => 'Sunucu hatası.',
            502 => 'Bad Gateway.',
            503 => 'Servis kullanılamıyor.',
            504 => 'Gateway Timeout.',
        ];

        return $messages[$statusCode] ?? 'Bilinmeyen hata.';
    }

    /**
     * Get error type for frontend
     */
    protected function getErrorType(Throwable $e): string
    {
        if ($e instanceof ValidationException) {
            return 'validation';
        }

        if ($e instanceof ModelNotFoundException) {
            return 'not_found';
        }

        if ($e instanceof QueryException) {
            return 'database';
        }

        if ($e instanceof AuthenticationException) {
            return 'authentication';
        }

        if ($e instanceof TokenMismatchException) {
            return 'csrf';
        }

        if ($e instanceof HttpException) {
            return 'http';
        }

        return 'general';
    }

    /**
     * Report or log an exception.
     */
    public function report(Throwable $e)
    {
        // Don't report certain exceptions
        if ($this->shouldntReport($e)) {
            return;
        }

        // Log the exception
        $this->logException($e);

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle specific exceptions
        if ($e instanceof ValidationException) {
            return $this->handleValidationException($e, $request);
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->handleModelNotFoundException($e, $request);
        }

        if ($e instanceof QueryException) {
            return $this->handleQueryException($e, $request);
        }

        if ($e instanceof AuthenticationException) {
            return $this->handleAuthenticationException($e, $request);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle validation exceptions
     */
    protected function handleValidationException(ValidationException $e, $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $e->errors(),
                'error_type' => 'validation'
            ], 422);
        }

        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
    }

    /**
     * Handle model not found exceptions
     */
    protected function handleModelNotFoundException(ModelNotFoundException $e, $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Kayıt bulunamadı.',
                'error_type' => 'not_found'
            ], 404);
        }

        toastr()->error('Kayıt bulunamadı.');
        return redirect()->back();
    }

    /**
     * Handle query exceptions
     */
    protected function handleQueryException(QueryException $e, $request)
    {
        Log::error('Database Error', [
            'sql' => $e->getSql(),
            'bindings' => $e->getBindings(),
            'message' => $e->getMessage(),
            'user_id' => Auth::id(),
            'url' => $request->fullUrl(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Veritabanı hatası oluştu.',
                'error_type' => 'database'
            ], 500);
        }

        toastr()->error('Veritabanı hatası oluştu. Lütfen tekrar deneyin.');
        return redirect()->back();
    }

    /**
     * Handle authentication exceptions
     */
    protected function handleAuthenticationException(AuthenticationException $e, $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Oturum süreniz dolmuş. Lütfen tekrar giriş yapın.',
                'error_type' => 'authentication'
            ], 401);
        }

        toastr()->error('Oturum süreniz dolmuş. Lütfen tekrar giriş yapın.');
        return redirect()->route('login');
    }
}
