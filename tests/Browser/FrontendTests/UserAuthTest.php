<?php

namespace Tests\Browser;

use Sitetpl\Models\Admin;
use Sitetpl\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;



class UserAuthTest extends DuskTestCase
{


    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testNoAccessToHomeWithoutLogin()
    {
        $this->browse(function ($browser) {
            $browser->visit('/home')
                ->assertPathIs('/login');
        });

    }

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testLoginRejectsInvalidUser()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('somepass')//
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'wrongpass')
                ->press('Login')
                ->assertPathIs('/login');
        });
        $user->delete();
    }
    
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testLoginAcceptsValidUser()
    {
        $password = 'password';
        $user = factory(User::class)->create([
            'password' => bcrypt($password)//
        ]);

        $this->browse(function ($browser) use ($user, $password) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', $password)
                ->press('Login')
                ->assertPathIs('/home');
        });
        $user->delete();
    }

}
