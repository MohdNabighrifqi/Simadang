<?php
// app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $pesan = 'Selamat datang kembali, ' . Auth::user()->name . '!';

        // Admin selalu masuk ke dashboard — abaikan URL tujuan lama (intended)
        // agar tidak "terbajak" oleh percobaan akses halaman lain saat masih tamu.
        if (Auth::user()->role === 'admin') {
            $request->session()->forget('url.intended');
            return redirect()->route('admin.dashboard')->with('success', $pesan);
        }

        return redirect()->intended(route('beranda'))->with('success', $pesan);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('beranda')
            ->with('success', 'Anda berhasil keluar.');
    }
}
