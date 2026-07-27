<?php

namespace Tests\Feature\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\Contracts\GlossaryQuizResultStoreInterface;
use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class GlossaryQuizResultsTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-quiz-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);

        app(UserRepository::class)->upsert([
            'id' => 'user_quiz',
            'email' => 'quiz@example.com',
            'displayName' => 'Quiz User',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_guest_cannot_post_quiz_results(): void
    {
        $this->postJson('/api/glossary/quiz-results', [
            'score' => 7,
            'total' => 10,
        ])->assertUnauthorized();
    }

    public function test_authenticated_user_can_save_and_see_results_on_profile(): void
    {
        $this->post('/login', [
            'email' => 'quiz@example.com',
            'password' => 'password123',
        ])->assertRedirect('/');

        $this->assertTrue(app(AccountAuth::class)->check());

        $this->postJson('/api/glossary/quiz-results', [
            'score' => 8,
            'total' => 10,
            'mode' => 'mixed',
        ])->assertOk()
            ->assertJsonPath('bestScore', 8)
            ->assertJsonPath('bestTotal', 10)
            ->assertJsonPath('attemptCount', 1);

        $stored = app(GlossaryQuizResultStoreInterface::class)->loadFor(app(AccountAuth::class)->user());
        $this->assertSame(1, $stored['attemptCount']);
        $this->assertSame(8, $stored['bestScore']);

        $profile = $this->get('/profile/settings');
        $profile->assertOk();
        $profile->assertSee('data-i18n="glossary.quiz.profileTitle"', false);
        $profile->assertSee('8 / 10', false);

        $quizPage = $this->get('/glossary');
        $quizPage->assertOk();
        $quizPage->assertSee('data-can-save="1"', false);
        $quizPage->assertSee('/api/glossary/quiz-results', false);
        $quizPage->assertSee('data-glossary-quiz-modal', false);
    }

    private function removeDir(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }
        $items = scandir($dir) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir.DIRECTORY_SEPARATOR.$item;
            if (is_dir($path)) {
                $this->removeDir($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}
