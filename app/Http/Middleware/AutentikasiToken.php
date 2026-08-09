<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AutentikasiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization');
        $token = null;

        if ($header && str_starts_with($header, 'Bearer ')) {
            $token = substr($header, 7);
        } else {
            $token = $request->query('token');
        }

        if (!$token) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Akses ditolak. Token autentikasi tidak ditemukan.',
            ], 401);
        }

        $user = User::where('token', $token)->first();

        if (!$user) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Akses ditolak. Token tidak valid atau sesi telah berakhir.',
            ], 401);
        }

        // Simpan instance user di request
        $request->attributes->set('user', $user);
        $request->attributes->set('pengguna', $user);

        return $next($request);
    }
}
