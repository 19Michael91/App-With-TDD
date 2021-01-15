<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Project;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testGuestCannotManageProjects()
    {
        $project = factory(Project::class)->create();

        $this->get('/projects')->assertRedirect('login');

        $this->get('/projects/create')->assertRedirect('login');

        $this->get($project->path() . '/edit')->assertRedirect('login');

        $this->get($project->path())->assertRedirect('login');

        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    public function testUserCanCreateProject()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = factory(Project::class)->raw(['owner_id' => auth()->id()]);

        $response = $this->followingRedirects()->post('/projects', $attributes);

        $this->assertDatabaseHas('projects', $attributes);

        $response
             ->assertSee($attributes['title'])
             ->assertSee(Str::limit($attributes['description'], 100))
             ->assertSee($attributes['notes']);
    }

    public function testTasksCanBeIncludedAsPartNewProjectCreation()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw();

        $attributes['tasks'] = [
            ['body' => 'Task 1'],
            ['body' => 'Task 2'],
        ];

        $this->post('/projects', $attributes);

        $this->assertCount(2, Project::first()->tasks);
    }

    public function testUserCanSeeAllProjectsTheyHaveBeenInvitedToOnTheirDashboard()
    {
        $user = $this->signIn();

        $project = ProjectFactory::create();

        $project->invite($user);

        $this->get('/projects')->assertSee($project->title);
    }

    public function testGuestCannotDeleteProject()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)->delete($project->path())->assertStatus(403);
    }

    public function testUserCanDeleteProject()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->delete($project->path())->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    public function testUserCanUpdateProject()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->patch($project->path(), $attributes = [
                'notes' => 'Test Notes',
                'title' => 'Test Title',
                'description' => 'Test Description',
            ])
            ->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertStatus(200);

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee('Test Notes');
    }

    public function testUserCanUpdateProjectsGeneralNotes()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->patch($project->path(), $attributes = ['notes' => 'Test Notes']);

        $this->assertDatabaseHas('projects', $attributes);
    }

    public function testUserCanViewTheirProject()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->get($project->path())
             ->assertSee($project->title)
             ->assertSee(Str::limit($project->description, 100));
    }

    public function testAuthenticatedUserCannotViewProjectsOfOthers()
    {
        $this->signIn();

        $project = factory(Project::class)->create();

        $this->get($project->path())->assertStatus(403);
    }

    public function testAuthenticatedUserCannotUpdateProjectsOfOthers()
    {
        $this->signIn();

        $project = factory(Project::class)->create();

        $this->patch($project->path(), ['notes' => 'Test Notes'])->assertStatus(403);
    }

    public function testProjectRequiresTitle()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function testProjectRequiresDescription()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
