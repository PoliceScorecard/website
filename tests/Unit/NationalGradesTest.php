<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class NationalGradesTest extends TestCase
{
    public function testNationalRankingsSelectTheLargestCompleteAgenciesOfEachType()
    {
        $states = ['CA' => [], 'NY' => []];

        foreach (['police-department', 'sheriff'] as $type) {
            for ($i = 1; $i <= 501; $i++) {
                $state = $i % 2 === 0 ? 'CA' : 'NY';
                $states[$state][$type][] = $this->agency($type, $i, $i * 1000, $i / 10);
            }

            // A large incomplete agency must not displace a complete agency.
            $states['CA'][$type][] = $this->agency($type, 502, 10000000, 99, false);
        }

        $original = $states;

        foreach (['police-department', 'sheriff'] as $type) {
            $grades = getNationalGrades($states, $type);
            $urls = array_column($grades['complete'], 'url_pretty');
            $expected = [];

            for ($i = 2; $i <= 501; $i++) {
                $expected[] = '/' . $type . '/' . $i;
            }

            // Population determines membership; score determines display order.
            $this->assertSame($expected, $urls);
            $this->assertCount(500, $grades['complete']);
            $this->assertFalse($grades['incomplete'][0]['complete']);
            $this->assertSame($grades['complete'], array_slice($grades['all'], 0, 500));
        }

        $this->assertSame($original, $states);
    }

    public function testPopulationTiesHaveAConsistentCutoff()
    {
        $agencies = [];

        for ($i = 501; $i >= 1; $i--) {
            $agencies[] = $this->agency('sheriff', sprintf('%03d', $i), 10000, 50);
        }

        $grades = getNationalGrades(['CA' => ['sheriff' => $agencies]], 'sheriff');
        $urls = array_column($grades['complete'], 'url_pretty');

        $this->assertCount(500, $urls);
        $this->assertContains('/sheriff/001', $urls);
        $this->assertNotContains('/sheriff/501', $urls);
    }

    public function testFewerThan500CompleteAgenciesKeepTheirScoreOrder()
    {
        $states = ['CA' => ['police-department' => [
            $this->agency('police-department', 1, 10000, 80),
            $this->agency('police-department', 2, 1000, 20),
            $this->agency('police-department', 3, null, 40),
            $this->agency('police-department', 4, 20000, 10, false),
        ]]];

        $grades = getNationalGrades($states, 'police-department');

        $this->assertSame([20, 40, 80], array_column($grades['complete'], 'overall_score'));
        $this->assertCount(1, $grades['incomplete']);
        $this->assertCount(4, $grades['all']);
        $this->assertSame(['complete' => [], 'incomplete' => [], 'all' => []], getNationalGrades([], 'sheriff'));
    }

    private function agency($type, $id, $population, $score, $complete = true)
    {
        return [
            'agency_name' => 'Agency ' . $id,
            'complete' => $complete,
            'grade_class' => 'f',
            'grade_letter' => 'F',
            'overall_score' => $score,
            'change_overall_score' => 0,
            'population' => $population,
            'title' => 'Agency ' . $id,
            'url_pretty' => '/' . $type . '/' . $id,
            'url' => '/' . $type . '/' . $id,
        ];
    }
}
