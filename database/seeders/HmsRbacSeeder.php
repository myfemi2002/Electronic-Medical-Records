<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class HmsRbacSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'records.access',
            'cashier.access',
            'triage.access',
            'doctor.access',
            'nurse.access',
            'pharmacy.access',
            'laboratory.access',
            'radiology.access',
            'hmo.access',
            'reports.view',
            'refund payment',
            'departments.manage',
            'rbac.manage',
            'security.manage',
            'system.settings',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $rolePermissions = [
            'Super Admin' => $permissions,
            'Records Officer' => ['records.access', 'reports.view'],
            'Cashier' => ['cashier.access', 'reports.view'],
            'Nurse' => ['triage.access', 'nurse.access', 'reports.view'],
            'Doctor' => ['doctor.access', 'reports.view'],
            'Lab Scientist' => ['laboratory.access', 'reports.view'],
            'Radiographer' => ['radiology.access', 'reports.view'],
            'Pharmacist' => ['pharmacy.access', 'reports.view'],
            'Inventory Manager' => ['pharmacy.access', 'laboratory.access', 'reports.view'],
            'HMO Officer' => ['hmo.access', 'cashier.access', 'reports.view'],
        ];

        foreach ($rolePermissions as $roleName => $rolePermissionSet) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($rolePermissionSet);
        }

        User::query()->where('role', 'admin')->get()->each(function (User $user): void {
            $user->syncRoles(['Super Admin']);
        });
    }
}
