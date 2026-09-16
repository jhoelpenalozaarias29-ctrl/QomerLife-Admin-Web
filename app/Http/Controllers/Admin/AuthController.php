<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Firebase\FirebaseAuthService;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Auth\SignIn\FailedToSignIn;

class AuthController extends Controller
{
    public function __construct(
        private readonly FirebaseAuthService $firebaseAuth,
        private readonly UserRepositoryInterface $users,
    ) {
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $authData = $this->firebaseAuth->signInWithPassword(
                $request->input('email'),
                $request->input('password')
            );
        } catch (FailedToSignIn) {
            return back()->withErrors([ 'email' => 'Credenciales incorrectas.' ])->withInput();
        }

        $user = $this->users->findByUid($authData['uid']);

        if (! $user) {
            return back()->withErrors([ 'email' => 'Usuario no encontrado en Firestore.' ])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));
        $this->users->updateLastLogin($user->uid);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
