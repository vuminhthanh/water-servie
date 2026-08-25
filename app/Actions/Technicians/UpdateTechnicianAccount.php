<?php

namespace App\Actions\Technicians;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UpdateTechnicianAccount
{
    public function execute(Technician $technician, array $accountData): Technician
    {
        if (User::where('email', $accountData['email'])
            ->where('id', '!=', $technician->user_id)
            ->exists()) {
            throw ValidationException::withMessages([
                'record.account_email' => 'Email này đã được sử dụng.',
            ]);
        }

        return DB::transaction(function () use ($technician, $accountData) {
            $user = User::query()->whereKey($technician->user_id)->lockForUpdate()->firstOrFail();
            $user->name = $accountData['name'];
            $user->email = $accountData['email'];

            if (!empty($accountData['password'])) {
                $user->password = Hash::make($accountData['password']);
            }

            $user->save();
            $technician->save();

            return $technician->fresh('user');
        });
    }
}
