<?php

namespace App\Application\Middleware;

use App\Infrastructure\Logging\MDCContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoggingContextMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $mdc = MDCContext::getInstance();

        // Capturar contexto de la solicitud
        $mdc->set('user_id', Auth::check() ? (string) Auth::id() : null);
        $mdc->set('user_ip', $request->ip() ?? '127.0.0.1');
        $mdc->set('method', $request->getMethod());
        $mdc->set('path', $request->getPathInfo());
        $mdc->set('user_agent', $request->userAgent() ?? 'Unknown');
        $mdc->set('hostname', gethostname() ?: 'unknown');
        $mdc->set('process_id', (string) getmypid());

        $response = $next($request);

        return $response;
    }
}
