<?php

namespace Tests\Unit;

use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserHasProjects()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    public function testUserHasAccessibleProjects()
    {
        $firstUser = $this->signIn();

        $firstUserProject = ProjectFactory::ownedBy($firstUser)->create();

        $this->assertCount(1, $firstUser->accessibleProjects());

        $secondUser = factory(User::class)->create();
        $thirdUser = factory(User::class)->create();

        $secondUserProject = ProjectFactory::ownedBy($secondUser)->create();
        $secondUserProject->invite($thirdUser);

        $this->assertCount(1, $firstUser->accessibleProjects());

        $secondUserProject->invite($firstUser);

        $this->assertCount(2, $firstUser->accessibleProjects());
    }
}
