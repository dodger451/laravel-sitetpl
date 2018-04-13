<?php

namespace Tests\Browser;

use Sitetpl\Models\Admin;
use Tests\Browser\Pages\AdminLoginPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Generator as Faker;


class AdminsControllerTest extends DuskTestCase
{

    private $faker;


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
    /**
     *
     * @return void
     */
    public function testAdminDetailsWorks()
    {
        $password = 'password';
        $user = factory(Admin::class)->create(['password' => bcrypt($password)]);

        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $browser
                //->visit(new AdminLoginPage())->loginWithCreds($user->email, $password)
                ->loginAs($user, 'admin')
                ->visit('/admin/admins/'.$user->id.'/')
                ->assertSee($user->name)
                ->assertSee($user->email)
                ->assertPathIs('/admin/admins/'.$user->id.'/');
        });
    }

    /**
     *
     * @return void
     */
    public function testAdminCreateWorks()
    {
        factory(Admin::class)->create();
        $this->browse(function (\Laravel\Dusk\Browser $browser) {

            $browser->loginAs(Admin::all()->first(), 'admin');
            $browser->visit('/admin/admins/create');

            $newName = 'name';
            $browser->type('@name-input', $newName);

            $newEmail = 'email@sitetpl.com';
            $browser->type('@email-input', $newEmail);

            $browser->click('@submit-button')
                ->assertPathIs('/admin/admins');

            $this->assertDatabaseHas(
                'admins',
                [
                    'name' => $newName,
                    'email' => $newEmail,
                ])
            ;

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
            $browser->loginAs(Admin::all()->first(), 'admin');

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
            ;

 
        });
    }

    /**
     *
     * @return void
     */
    public function testAdminEditChangesData()
    {
        $password = 'password';
        $user = factory(Admin::class)->create([
            'password' => bcrypt($password)//
        ]);

        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $newName = '_' . $user->name;
            $newEmail = '_' . $user->email;
            $browser->loginAs($user, 'admin');
            $browser->visit('/admin/admins/'.$user->id.'/edit')
                ->assertInputValue('@name-input', $user->name)
                ->type('@name-input', $newName)
                ->assertInputValue('@email-input', $user->email)
                ->type('@email-input', $newEmail)
                ->click('@submit-button')
                ->assertPathIs('/admin/admins');
            $browser->visit('/admin/admins/'.$user->id.'/edit')
                ->assertInputValue('@name-input', $newName)
                ->assertInputValue('@email-input', $newEmail);
        });
    }
    /**
     *
     * @return void
     */
    public function testAdminEditValidates()
    {
        $password = 'password';
        $user = factory(Admin::class)->create([
            'password' => bcrypt($password)//
        ]);

        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $browser->visit('/admin/admins/'.$user->id.'/edit')
                ->assertInputValue('@name-input', $user->name);
            //name
            $browser->type('@name-input', '')
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/'.$user->id.'/edit')
                ->assertVisible('@name-error')
                ;

            $otherUser = factory(Admin::class)->create();
            $browser->type('@name-input', $otherUser->name)
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/'.$user->id.'/edit')
                ->assertVisible('@name-error')
            ;

            //email
            $browser->type('@email-input', '')
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/'.$user->id.'/edit')
                ->assertVisible('@email-error')
            ;

            $browser->type('@email-input', 'email.com')
                ->click('@submit-button')
                ->assertPathIs('/admin/admins/'.$user->id.'/edit')
                ->assertVisible('@email-error')
            ;
            
        });
    }
}