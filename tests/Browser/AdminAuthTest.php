<?php

namespace Tests\Browser;

use Sitetpl\Models\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;



class AdminAuthTest extends DuskTestCase
{


    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testNoAccessWithoutLogin()
    {
        $this->browse(function ($browser) {
            $browser->visit('/admin/home')
                ->assertPathIs('/login');
        });

    }
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testLoginAcceptsValidAdmin()
    {
        $password = 'password';
        $user = factory(Admin::class)->create([
            'password' => bcrypt($password)//
        ]);

        $this->browse(function ($browser) use ($user, $password) {
            $browser->visit('/admin/login')
                ->type('email', $user->email)
                ->type('password', $password)
                ->press('Login')
                ->assertPathIs('/admin/home');
        });
        $user->delete();
    }
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testLoginRejectsInvalidAdmin()
    {
        $user = factory(Admin::class)->create([
            'password' => bcrypt('somepass')//
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/admin/login')
                ->type('email', $user->email)
                ->type('password', 'wrongpass')
                ->press('Login')
                ->assertPathIs('/admin/login');
        });
        $user->delete();
    }
}
