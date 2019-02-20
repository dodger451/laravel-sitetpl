<?php

namespace Tests\Browser;

use Sitetpl\Models\Admin;
use Tests\Browser\Pages\AdminLoginPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Generator as Faker;

class AdminsCreateTest extends DuskTestCase
{

    /**
     *
     * @return void
     */
    public function testAdminCreateInserts()
    {
        $password = 'password';
        $user = factory(Admin::class)->create(['password' => bcrypt($password)//
        ]);
        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $browser->visit(new AdminLoginPage());

            echo 'base:'.$browser::$baseUrl.PHP_EOL;
            echo 'visit new AdminLoginPage'.PHP_EOL;
            echo '------------------------'.PHP_EOL;
            echo($browser->driver->getPageSource());
            $browser->loginWithCreds($user->email, $password);
            echo 'loginWithCreds'.PHP_EOL;
            echo '--------------'.PHP_EOL;
            echo($browser->driver->getPageSource());

//            $browser->loginAs($user, 'admin');
            $browser->visit('/admin/admins/create');
            echo 'visit /admin/admins/create'.PHP_EOL;
            echo '--------------------------'.PHP_EOL;
            echo($browser->driver->getPageSource());

            $newName = 'name';
            $browser->type('@name-input', $newName);

            $newEmail = 'email@sitetpl.com';
            $browser->type('@email-input', $newEmail);

            $browser->click('@submit-button')
                ->assertPathIs('/admin/admins');

            $this->assertDatabaseHas('admins', ['name' => $newName, 'email' => $newEmail,]);
        });
    }

    /**
     *
     * @return void
     */
    public function testAdminCreateValidates()
    {

        factory(Admin::class)->create();

        $this->browse(function (\Laravel\Dusk\Browser $browser) {
            $browser->loginAs(Admin::all()
                ->first(), 'admin');

            $browser->visit('/admin/admins/create');
            //name
            $browser->type('@name-input', '')
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/create')
                ->assertVisible('@name-error');

            $otherUser = factory(Admin::class)->create();
            $browser->type('@name-input', $otherUser->name)
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/create')
                ->assertVisible('@name-error')
                ->type('@name-input', 'new_valid_name_testAdminCreateValidates');

            //email
            $browser->type('@email-input', '')
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/create')
                ->assertVisible('@email-error')
                ->type('@email-input', 'testAdminCreateValidates@sitetpl.com');

            $browser->type('@email-input', 'email.com')
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/create')
                ->assertVisible('@email-error')
                ->type('@email-input', 'testAdminCreateValidates@sitetpl.com');
        });
    }
}
