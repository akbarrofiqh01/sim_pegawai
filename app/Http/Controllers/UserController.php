<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function impersonate($codeUser)
    {
        $targetUser  = User::where('code_user', $codeUser)->firstOrFail();
        $currentUser = auth()->user();

        if (!$currentUser->hasRole(['superadmin', 'admin'])) {
            abort(403, 'Tidak memiliki izin.');
        }

        Session::put('impersonate', $currentUser->code_user);
        Auth::login($targetUser);

        return response()->json([
            'message'  => 'Berhasil masuk sebagai ' . $targetUser->name,
            'redirect' => route('dashboard'), // sesuaikan
            'role'     => $targetUser->getRoleNames()->first(),
        ]);
    }

    public function stopImpersonate()
    {
        // dd($originalUser);
        if (Session::has('impersonate')) {
            $originalUser = User::where('code_user', Session::pull('impersonate'))->firstOrFail();

            if ($originalUser) {
                Auth::login($originalUser); // kembali ke superadmin
                return redirect()->route('dashboard')->with('success', 'Kembali ke akun superadmin.');
            }
        }

        return redirect('/')->with('error', 'Tidak sedang impersonate.');
    }
}
