<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateAnnouncementsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_is_require_a_title()
    {
        $this->publishAnnouncement(['title' => null])
             ->assertSessionHasErrors('title');
    }

    /** @test */
    public function it_is_require_a_summary()
    {
        $this->publishAnnouncement(['summary' => null])
             ->assertSessionHasErrors('summary');
    }

    /** @test */
    public function it_is_require_a_body()
    {
        $this->publishAnnouncement(['body' => null])
             ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_feature_image_accept_valid_images_only()
    {
        $file = UploadedFile::fake()->image('dummy.err');
        $this->publishAnnouncement(['featured_image' => $file])
             ->assertSessionHasErrors('featured_image');
    }

    public function publishAnnouncement($overrides = [])
    {
        $this->withExceptionHandling();
        $this->signInSuperAdmin();

        $announcement = make(Post::class, $overrides);

        return $this->post(route('admin.posts.store'), $announcement->toArray());
    }

    /** @test */
    public function a_guest_cannot_add_new_announcement()
    {
        $this->withExceptionHandling();

        $this->post(route('admin.posts.store'))->assertStatus(302)->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function a_user_cannot_add_new_announcement()
    {
        $this->withExceptionHandling();

        $this->post(route('admin.posts.store'))->assertStatus(302)->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function an_admin_can_create_new_announcement_if_it_was_permitted()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function an_admin_cannot_create_new_announcement_if_not_permitted()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function a_super_admin_can_add_announcement()
    {
        // $this->signInSuperAdmin();

        // $user = create(User::class, ['id' => 1]);
        // $this->actingAs($user);

        // $user = create(User::class);
        // $role = create(Role::class);
        // $permission = create(Permission::class, ['role_id' => $role->id]);
        // $rolePermission = $role->permissions();

        // $user->roles()->save($role);

        // $file = UploadedFile::fake()->image('image.jpg');

        // $request = $this->publishAnnouncement([
        //     'user_id'        => auth()->id(),
        //     'title'          => 'My Title',
        //     'summary'        => 'My Summary',
        //     'body'           => 'My Body',
        //     'featured_image' => $file,
        //     'status'         => 'draft',
        //     'pinned'         => true,
        // ]);
        $this->markTestIncomplete();
    }
}
