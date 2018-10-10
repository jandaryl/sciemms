<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_be_instantiated()
    {
        $user = create(User::class);

        $this->assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function it_can_get_the_slug_attributes()
    {
        $user = create(User::class, ['name' => 'jandaryl']);

        $this->assertEquals($user->slug, 'jandaryl');
    }

    /** @test */
    public function it_can_get_the_can_edit_attribute()
    {
        $user = create(User::class);

        $this->assertFalse($user->can_edit);
    }

    /** @test */
    public function it_can_get_the_can_delete_attribute()
    {
        $user = create(User::class);

        $this->assertFalse($user->can_delete);
    }

    /** @test */
    public function it_can_get_the_can_impersonate_attribute()
    {
        $user = create(User::class);

        $this->assertFalse($user->can_impersonate);
    }

    /** @test */
    public function it_can_scope_by_active_users()
    {
        $user = create(User::class, ['active' => true], 2);
        $user = create(User::class, ['active' => false]);

        $this->assertEquals($user->actives()->count(), 2);
    }

    /** @test */
    public function it_can_get_the_is_super_admin_attribute()
    {
        $user = create(User::class, ['id' => 1]);

        $this->assertTrue($user->is_super_admin);

        $user = create(User::class, ['id' => 2]);

        $this->assertFalse($user->is_super_admin);
    }

    /** @test */
    public function it_is_belongs_to_many_roles()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_get_the_formatted_roles_attribute()
    {
        $user = create(User::class);

        $this->assertEquals($user->formatted_roles, 'Super Administrator');

        $role = create(Role::class, ['display_name' => 'Administrator']);

        $formatted_role = $user->roles()->save($role)->display_name;

        $this->assertEquals($formatted_role, 'Administrator');
    }

    /** @test */
    public function it_has_a_role_name()
    {
        $user = create(User::class);
        $role = create(Role::class, ['name' => 'Admin']);
        $role_name = $user->roles()->save($role)->name;

        $this->assertEquals($role_name, 'Admin');
    }

    /** @test */
    public function it_can_get_permissions_from_the_role()
    {
        $user = create(User::class);
        $role = create(User::class, ['name' => 'admin']);
        $permissions = create(Permission::class, [
            'role_id' => $role->id,
            'name' => 'access backend'
        ]);

        $role_id = $user->roles()->save($role)->id;
        /**
         * This test is not accurate to the main idea.
         */
        $this->assertEquals($permissions->role_id, $role_id);
        $this->assertEquals($permissions->name, 'access backend');
    }

    /** @test */
    public function it_can_send_password_reset_notifications()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_get_the_social_provider()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_has_many_social_providers()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_get_the_avatar_attribute()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_has_many_posts()
    {
        $this->markTestIncomplete();
    }
}
