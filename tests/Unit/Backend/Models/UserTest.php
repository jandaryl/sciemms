<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // /** @test */
    // public function it_stub()
    // {
    //     $this->signInAdmin();

    // }
    public function setup()
    {
        parent::setup();

        $this->user = create(User::class, ['name' => 'jandaryl', 'active' => true]);
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    /** @test */
    public function it_can_get_the_slug_attributes()
    {
        $this->assertEquals($this->user->slug, 'jandaryl');
    }

    /** @test */
    public function it_can_get_the_can_edit_attribute()
    {
        $this->assertFalse($this->user->can_edit);
    }

    /** @test */
    public function it_can_get_the_can_delete_attribute()
    {
        $this->assertFalse($this->user->can_delete);
    }

    /** @test */
    public function it_can_get_the_can_impersonate_attribute()
    {
        $this->assertFalse($this->user->can_impersonate);
    }

    /** @test */
    public function it_can_scope_by_active_users()
    {
        $this->assertEquals($this->user->actives()->count(), 1);
    }

    /** @test */
    public function it_can_get_the_is_super_admin_attribute()
    {
        $superAdmin = $this->user;
        $otherUser = create(User::class, ['id' => 2]);

        $this->assertTrue($superAdmin->is_super_admin);
        $this->assertFalse($otherUser->is_super_admin);
    }

    /** @test */
    public function it_is_belongs_to_many_roles()
    {
        $roles = create(Role::class, [], 2);

        $rolesCount = $this->user->roles()->saveMany($roles)->count();

        $this->assertEquals($rolesCount, 2);
        $this->assertInstanceOf(Collection::class, $this->user->roles);
    }

    /** @test */
    public function it_can_get_the_formatted_roles_attribute()
    {
        $role = create(Role::class, ['display_name' => 'Administrator']);

        $formatted_role = $this->user->roles()->save($role)->display_name;

        $this->assertEquals($this->user->formatted_roles, 'Super Administrator');
        $this->assertEquals($formatted_role, 'Administrator');
    }

    /** @test */
    public function it_has_a_role_name()
    {
        $role = create(Role::class, ['name' => 'Admin']);

        $roleName = $this->user->roles()->save($role)->name;

        $this->assertEquals($roleName, 'Admin');
    }

    /** @test */
    public function it_can_get_permissions_from_the_role()
    {
        $role = create(Role::class, ['name' => 'admin']);
        $permissions = create(Permission::class, [
            'role_id' => $role->id,
            'name'    => 'access backend'
        ]);

        $roleId = $this->user->roles()->save($role)->id;

        $this->assertEquals($permissions->role_id, $roleId);
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

    // /** @test */
    // public function it_has_many_posts()
    // {
    //     $user = $this->user;
    //     $posts = create(Post::class, [], 2);

    //     $postsCount = $user->posts()->saveMany($posts)->count();

    //     $this->assertEquals($postsCount, 2);
    //     $this->assertInstanceOf(Collection::class, $user->posts);
    //     $posts->each(function (Post $posts) use ($user) {
    //         $this->assertEquals($posts->user_id, $user->id);
    //     });
    // }
}
