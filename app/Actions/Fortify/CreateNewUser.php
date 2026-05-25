<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->where(function ($query) {
                    return $query->where('tenant_id', session('tenant_id'));
                }),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'tenant_id' => session('tenant_id'),
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'customer',
        ]);

        // Create Customer Profile and Auto-Generate Box Numbers
        $tenant = \App\Models\Tenant::find($user->tenant_id);
        if ($tenant) {
            $boxNumberAir = $tenant->generateBoxNumber($user->name, 'air');
            $boxNumberMaritime = $tenant->generateBoxNumber($user->name, 'maritime');

            // Increment the counter once after both are generated
            $tenant->incrementCounter();

            \App\Models\Customer::create([
                'tenant_id' => $user->tenant_id,
                'user_id' => $user->id,
                'box_number' => $boxNumberAir, // Legacy fallback
                'box_number_air' => $boxNumberAir,
                'box_number_maritime' => $boxNumberMaritime,
                'balance' => 0,
                'points' => 0,
            ]);
        }

        return $user;
    }
}
