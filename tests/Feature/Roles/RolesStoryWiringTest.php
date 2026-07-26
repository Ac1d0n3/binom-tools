<?php

namespace Tests\Feature\Roles;

use App\Playbooks\PlaybookRepository;
use Tests\TestCase;

class RolesStoryWiringTest extends TestCase
{
    public function test_roles_prefer_new_stories_when_present(): void
    {
        $playbooks = app(PlaybookRepository::class);
        $this->assertNotNull($playbooks->find('data-architect-role'));
        $this->assertNotNull($playbooks->find('raci-for-data-governance'));
        $this->assertNotNull($playbooks->find('stewardship-capacity'));
        $this->assertNotNull($playbooks->find('data-product-owner-vs-data-owner'));
        $this->assertNotNull($playbooks->find('governance-coe'));

        $architect = $this->get('/roles/architect');
        $architect->assertOk();
        $architect->assertSee('/playbooks/data-architect-role', false);
        $architect->assertSee('/playbooks/raci-for-data-governance', false);
        $architect->assertDontSee('roles.pendingStoriesHint', false);

        $steward = $this->get('/roles/steward');
        $steward->assertOk();
        $steward->assertSee('/playbooks/stewardship-capacity', false);

        $productOwner = $this->get('/roles/product-owner');
        $productOwner->assertOk();
        $productOwner->assertSee('/playbooks/data-product-owner-vs-data-owner', false);

        $this->get('/learning-paths/governance-foundations')
            ->assertOk()
            ->assertSee('/playbooks/series/roles-hub', false)
            ->assertSee('/playbooks/governance-coe', false);

        $this->get('/glossary/data-architect')
            ->assertOk()
            ->assertSee('/playbooks/data-architect-role', false);

        $this->get('/glossary/raci')
            ->assertOk()
            ->assertSee('/playbooks/raci-for-data-governance', false);
    }
}
