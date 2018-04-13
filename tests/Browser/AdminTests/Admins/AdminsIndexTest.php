<?php

namespace Tests\Browser;

use Sitetpl\Models\Admin;
use Tests\Browser\Pages\AdminLoginPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Generator as Faker;


class AdminsIndexTest extends DuskTestCase
{

    /**
     *
     * @return void
     */
    public function testAdminsListWorks()
    {
        $password = 'password';
        $user = factory(Admin::class)->create([
            'password' => bcrypt($password)//
        ]);
        
        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $browser
                //->loginAs($user, 'admin')
                ->visit(new AdminLoginPage())->loginWithCreds($user->email, $password)
                ->visit('/admin/admins')
                ->assertPathIs('/admin/admins');

        });
    }
    
}