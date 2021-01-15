<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;
use App\User;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function testNonOwnersMayNotInviteUsers()
    {
        $project = ProjectFactory::create();

        $user = factory(User::class)->create();

        $assertInvitationForbidden = function() use ($user, $project){
            $this->actingAs($user)
                ->post($project->path() . '/invitations')
                ->assertStatus(403);
        };

        $assertInvitationForbidden();

        $project->invite($user);

        $assertInvitationForbidden();
    }

    public function testProjectOwnerCanInviteUser()
    {
        $project = ProjectFactory::create();

        $userToInvite = factory(User::class)->create();

        $this->actingAs($project->owner)
             ->post($project->path() . '/invitations', [
                 'email' => $userToInvite->email
             ])
             ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    public function testEmailAddressMustBeAccociatedWithValidAccount()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->post($project->path() . '/invitations', [
            'email' => 'notUser@example.com'
        ])->assertSessionHasErrors([
            'email' => 'The user you are inviting must have a Birdboard account.'
        ], null, 'invitations');
    }

    public function testInvitedUsersMayUpdateDetails()
    {
        $project = ProjectFactory::create();

        $project->invite($newUser = factory(User::class)->create());

        $this->signIn($newUser);

        $this->post(action('ProjectsTasksController@store', $project),  $task = ['body' => 'Test Task']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
