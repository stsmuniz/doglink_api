<?php

namespace Database\Seeders;

use App\Models\User;
use Artesaos\Defender\Facades\Defender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin = Defender::createRole('admin');
        $roleFreeUser = Defender::createRole('freeUser');
        $rolePremiumUser = Defender::createRole('premiumUser');

        $userListPermission = Defender::createPermission('user.list', 'List Users');
        $userEditPermission = Defender::createPermission('user.edit', 'Edit User');
        $userDeletePermission = Defender::createPermission('user.delete', 'Delete Users');

        $roleAdmin->attachPermission($userListPermission);
        $roleAdmin->attachPermission($userEditPermission);
        $roleAdmin->attachPermission($userDeletePermission);

        $pageListPermission = Defender::createPermission('page.list', 'List Pages');
        $pageCreatePermission = Defender::createPermission('page.create', 'Create Page');
        $pageEditPermission = Defender::createPermission('page.edit', 'Edit Page');
        $pageDeletePermission = Defender::createPermission('page.delete', 'Delete Page');

        $rolePremiumUser->attachPermission($pageCreatePermission);
        $rolePremiumUser->attachPermission($pageListPermission);
        $rolePremiumUser->attachPermission($pageDeletePermission);

        $admin = User::where('email', 'contato@doglink.net')->first();
        $admin->attachRole($roleAdmin);

        $premium = User::where('email', 'stsmuniz@gmail.com')->first();
        $premium->attachRole($rolePremiumUser);
    }
}
