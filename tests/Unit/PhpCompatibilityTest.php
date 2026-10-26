<?php

namespace Tests\Unit;

use Tests\TestCase;

class PhpCompatibilityTest extends TestCase
{
    public function testMissingAndNullFieldsAreExcludedWithoutWarnings(): void
    {
        $this->assertSame([['name' => 'Agency']], removeEmpty([
            ['other' => 'value'], ['name' => null], ['name' => ' '], ['name' => 'Agency'],
        ], 'name'));
    }

    public function testStateLinksInterpolateTheirValues(): void
    {
        $link = generateStateLink('CA', 'CA');
        $this->assertStringContainsString('href="/ca"', $link);
        $this->assertStringContainsString('California', $link);
        $this->assertStringContainsString('state-link active', $link);
    }

    public function testGradeSortingPreservesAscendingScoresAndEqualScores(): void
    {
        $grades = sortGrades([
            ['complete' => true, 'overall_score' => 80],
            ['complete' => true, 'overall_score' => 20],
            ['complete' => true, 'overall_score' => 20],
        ]);
        $this->assertSame([20, 20, 80], array_column($grades['complete'], 'overall_score'));
    }

    public function testAdminAcceptsConfiguredCredentials(): void
    {
        config(['admin.auth_user' => 'tester', 'admin.auth_pass' => 'test-password']);
        $this->withServerVariables(['PHP_AUTH_USER' => 'tester', 'PHP_AUTH_PW' => 'test-password'])
            ->get('/admin')->assertOk()->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, private');
    }

    public function testSearchUsesTheUpgradedHttpClient(): void
    {
        \Illuminate\Support\Facades\Http::preventStrayRequests();
        $results = [['agency_name' => 'Test Agency']];
        \Illuminate\Support\Facades\Http::fake(['*' => \Illuminate\Support\Facades\Http::response(['data' => $results])]);
        $this->postJson('/api/search', ['keyword' => 'test'])->assertOk()->assertExactJson($results);
        \Illuminate\Support\Facades\Http::assertSentCount(1);
    }

    public function testPublicRoutesAndAdminAuthenticationBoot(): void
    {
        \Illuminate\Support\Facades\Http::preventStrayRequests();
        \Illuminate\Support\Facades\Http::fake(['*' => \Illuminate\Support\Facades\Http::response(['data' => []])]);
        $this->get('/about')->assertOk();
        $this->get('/findings')->assertOk();
        $this->get('/admin')->assertUnauthorized();
    }
}
