<?php

namespace Tests\Browser;

use Sitetpl\Models\Admin;
use Tests\Browser\Pages\AdminLoginPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Generator as Faker;


class AdminsShowTest extends DuskTestCase
{

    /**
     *
     * @return void
     */
    public function testAdminShowSelects()
    {
        $password = 'password';
        $user = factory(Admin::class)->create(['password' => bcrypt($password)]);

        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $browser
                ->visit(new AdminLoginPage())->loginWithCreds($user->email, $password)
                ->loginAs($user, 'admin')
                ->visit('/admin/admins/'.$user->id.'/')
                ->assertSee($user->name)
                ->assertSee($user->email)
                ->assertPathIs('/admin/admins/'.$user->id.'/');
        });
    }

}