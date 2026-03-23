<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        return view('store.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Invalid credentials.'])
                ->withInput($request->except('password'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('store.account'));
    }

    public function showRegister(Request $request)
    {
        return view('store.auth.register', [
            'ref' => $request->query('ref'),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string|max:32',
        ]);

        $referrerId = null;
        if (! empty($validated['referral_code'])) {
            $referrer = User::query()->where('referral_code', $validated['referral_code'])->first();
            $referrerId = $referrer?->id;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'referral_code' => $this->generateReferralCode($validated['name']),
            'referred_by' => $referrerId,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('store.account')->with('status', 'Welcome! Your account is ready.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('store.home');
    }

    private function generateReferralCode(string $name): string
    {
        $prefix = Str::upper(Str::substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 4) ?: 'NN');

        do {
            $code = $prefix . Str::upper(Str::random(6));
        } while (User::query()->where('referral_code', $code)->exists());

        return $code;
    }
}
