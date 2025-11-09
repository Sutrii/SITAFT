<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $username = trim($this->input('username'));
        $password = $this->input('password');

        $user = null;

        $mahasiswa = \App\Models\Mahasiswa::where('nim', $username)->first();
        if ($mahasiswa && $mahasiswa->userId) {
            $user = \App\Models\User::find($mahasiswa->userId);
        }

        if (! $user) {
            $nipUser = \App\Models\User::whereNotNull('nip')
                ->where('nip', $username)
                ->first();

            if ($nipUser) {
                $user = $nipUser;
            }
        }

        if (! $user) {
            \Illuminate\Support\Facades\RateLimiter::hit($this->throttleKey());
            throw \Illuminate\Validation\ValidationException::withMessages([
                'username' => 'Akun tidak ditemukan. Periksa kembali NIM/NIP Anda.',
            ]);
        }

        if (! \Hash::check($password, $user->password)) {
            \Illuminate\Support\Facades\RateLimiter::hit($this->throttleKey());
            throw \Illuminate\Validation\ValidationException::withMessages([
                'password' => 'Password salah untuk akun ' . $user->name,
            ]);
        }

        \Illuminate\Support\Facades\Auth::login($user, $this->boolean('remember'));

        \Illuminate\Support\Facades\RateLimiter::clear($this->throttleKey());
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::lower($this->input('username')).'|'.$this->ip();
    }
}
