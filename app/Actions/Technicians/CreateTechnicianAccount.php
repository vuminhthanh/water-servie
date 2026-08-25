<?php

namespace App\Actions\Technicians;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CreateTechnicianAccount
{
    public function execute(array $data): Technician
    {
        if (User::where('email', $data['account_email'])->exists()) {
            throw ValidationException::withMessages([
                'record.account_email' => 'Email này đã được sử dụng.',
            ]);
        }

        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['account_name'],
                'email' => $data['account_email'],
                'password' => Hash::make($data['account_password']),
            ]);

            unset(
                $data['account_name'],
                $data['account_email'],
                $data['account_password'],
                $data['account_password_confirmation']
            );

            $data['user_id'] = $user->id;

            return Technician::create($data)->fresh('user');
        });
    }
}
