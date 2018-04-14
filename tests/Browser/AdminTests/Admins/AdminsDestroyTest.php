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
    public function testAdminsListDestroyDeletes()
    {
        $password = 'password';
        $user = factory(Admin::class)->create([
            'password' => bcrypt($password)//
        ]);
        $user2= factory(Admin::class)->create();
        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password, $user2) {
            $browser
                ->visit(new AdminLoginPage())->loginWithCreds($user->email, $password)
                ->visit('/admin/admins')
                ->assertSee($user2->name)
                ->assertVisible('@delete-button-' . $user2->id);

            $browser->click('@delete-button-' . $user2->id)
                ->assertPathIs('/admin/admins')
                ->assertDontSee($user2->name);

        });
    }
    
}