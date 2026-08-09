<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HanyaAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->peran !== 'admin') {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Akses ditolak. Fitur ini hanya dapat diakses oleh Admin.',
            ], 403);
        }

        return $next($request);
    }
}
