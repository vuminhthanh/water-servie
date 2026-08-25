<?php

namespace Tests\Feature;

use App\Actions\Technicians\CreateTechnicianAccount;
use App\Actions\Technicians\UpdateTechnicianAccount;
use App\Filament\Resources\TechnicianResource\Pages\EditTechnician;
use Filament\Models\User as FilamentUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class TechnicianAccountManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_creates_and_updates_a_technician_login_account()
    {
        $technician = app(CreateTechnicianAccount::class)->execute([
            'account_name' => 'Kỹ thuật viên đăng nhập',
            'account_email' => 'technician.login@example.test',
            'account_password' => 'secure-password',
            'account_password_confirmation' => 'secure-password',
            'technician_code' => 'TECH-LOGIN-001',
            'phone' => '0901234567',
            'working_status' => 'available',
        ]);

        $this->assertSame('technician.login@example.test', $technician->user->email);
        $this->assertTrue(Hash::check('secure-password', $technician->user->password));

        $technician->phone = '0912345678';
        $updated = app(UpdateTechnicianAccount::class)->execute($technician, [
            'name' => 'Kỹ thuật viên đã sửa',
            'email' => 'technician.updated@example.test',
            'password' => 'new-secure-password',
        ]);

        $this->assertSame('0912345678', $updated->phone);
        $this->assertSame('technician.updated@example.test', $updated->user->email);
        $this->assertTrue(Hash::check('new-secure-password', $updated->user->password));

        $admin = FilamentUser::create([
            'name' => 'Admin Test',
            'email' => uniqid() . '@admin.test',
            'password' => Hash::make('admin-password'),
            'is_admin' => true,
            'roles' => [],
        ]);

        Livewire::actingAs($admin, 'filament')
            ->test(EditTechnician::class, ['record' => $updated->id])
            ->assertSet('record.account_name', 'Kỹ thuật viên đã sửa')
            ->assertSet('record.account_email', 'technician.updated@example.test')
            ->set('record.account_password', 'password-from-edit-form')
            ->set('record.account_password_confirmation', 'password-from-edit-form')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertTrue(
            Hash::check('password-from-edit-form', $updated->user()->first()->password)
        );
    }
}
