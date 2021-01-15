<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;

use App\Task;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatingProjectRecordsActivity()
    {
        $project = ProjectFactory::create();

        $project->addTask('Test Task');

        $this->assertCount(2, $project->activity);
        $this->assertEquals('created_project', $project->activity->first()->description);
        $this->assertInstanceOf(Task::class, $project->activity->last()->subject);
        $this->assertEquals('Test Task', $project->activity->last()->subject->body);
    }

    public function testUpdatingProjectRecordsActivity()
    {
        $project = ProjectFactory::create();

        $originalTitle = $project->title;

        $project->update([
            'title' => 'Change Title'
        ]);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function($activity) use ($originalTitle){

            $this->assertEquals('updated_project', $activity->description);

            $expected = [
                'before' => ['title' => $originalTitle],
                'after' => ['title' => 'Change Title'],
            ];

            $this->assertEquals($expected, $activity->changes);
        });
    }

    public function testCreatingNewTaskRecordsProjectActivity()
    {
        $project = ProjectFactory::create();

        $project->addTask('Some Task');

        $this->assertCount(2, $project->activity);

        $this->assertEquals('created_task', $project->activity->last()->description);
    }

    public function testCompletingTask()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), [
            'body' => 'foobar',
            'completed' => true,
        ]);

        $this->assertCount(3, $project->activity);

        $this->assertInstanceOf(Task::class, $project->activity->last()->subject);
        $this->assertEquals('completed_task', $project->activity->last()->description);
    }

    public function testIncompletingTask()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), [
            'body' => 'foobar',
            'completed' => true,
        ]);

        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks->first()->path(), [
            'body' => 'foobar',
            'completed' => false,
        ]);

        $project->refresh();

        $this->assertCount(4, $project->activity);

        $this->assertEquals('incompleted_task', $project->activity->last()->description);
    }

    public function testDeletingTask()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $project->tasks->first()->delete();

        $this->assertCount(3, $project->activity);
    }
}
