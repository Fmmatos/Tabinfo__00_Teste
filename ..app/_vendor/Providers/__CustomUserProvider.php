<?php

namespace Vendor\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Vendor\Models\Customers;

class __CustomUserProvider
// class __CustomUserProvider implements UserProvider
{
    public function retrieveById(mixed $identifier): mixed
    {
        return Customers::select('id')
            ->where('id', $identifier)
            ->where('active', 1)
            ->first();
    }

    public function retrieveByToken(mixed $identifier, mixed $token): mixed
    {
        return Customers::where('id', $identifier)
            ->where('remember_token', $token)
            ->first();
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        $user->setRememberToken($token);
    
        // Cast opcional se precisar de métodos específicos
        if ($user instanceof \Vendor\Models\Customers) {
            $user->save();
        }
    }

    public function retrieveByCredentials(array $credentials): mixed
    {
        return Customers::where('email', $credentials['email'] ?? null)->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }
}
