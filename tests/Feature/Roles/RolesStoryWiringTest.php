<?php

namespace Tests\Feature\Roles;

use App\Playbooks\PlaybookRepository;
use Tests\TestCase;

class RolesStoryWiringTest extends TestCase
{
    public function test_roles_prefer_dedicated_stories_when_present(): void
    {
        $playbooks = app(PlaybookRepository::class);
        $this->assertNotNull($playbooks->find('data-architect-role'));

        $http = $this->get('/roles/architect');
        $http->assertOk();
        $http->assertSee('/playbooks/data-architect-role', false);
        $http->assertDontSee('data-i18n="roles.pendingStoriesHint"', false);
    }
}
