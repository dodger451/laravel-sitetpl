<?php

namespace Tests\Browser;

use Sitetpl\Models\User;
use Tests\Browser\Pages\AdminLoginPage;
use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Generator as Faker;

class ProfileShowTest extends DuskTestCase
{
    /**
     *
     * @return void
     */
    public function testProfileShowSelects()
    {
        $password = 'password';
        $user = factory(User::class)->create(['password' => bcrypt($password)]);

        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $browser->visit(new LoginPage())
                ->loginWithCreds($user->email, $password)
                ->loginAs($user, 'admin')
                ->visit('/profile/')
                ->assertSee($user->name)
                ->assertSee($user->email)
                ->assertPathIs('/profile/');
        });
    }
}
