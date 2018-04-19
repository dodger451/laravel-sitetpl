<?php

namespace Tests\Browser;

use Sitetpl\Http\Controllers\Admin\AdminsController;
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
                ->visit(new AdminLoginPage())->loginWithCreds($user->email, $password)
                ->visit('/admin/admins')
                ->assertPathIs('/admin/admins');

        });
    }
    /**
     *
     * @return void
     */
    public function testAdminsListPaginates()
    {
        $password = 'password';
        $user = factory(Admin::class)->create([
            'password' => bcrypt($password)//
        ]);

        for($i = 0; $i < (AdminsController::PAGESIZE); $i++) {
            factory(Admin::class)->create();
        }

        $this->browse(function (\Laravel\Dusk\Browser $browser) use ($user, $password) {
            $browser
                ->visit(new AdminLoginPage())->loginWithCreds($user->email, $password)
                ->visit('/admin/admins')
                ->assertVisible('@next-page')
                ->click('@next-page');
            $browser
                ->assertPathIs('/admin/admins')
                ->assertQueryStringHas('page', '2')
                ->assertVisible('@list-item');
        });
    }
    
    
}