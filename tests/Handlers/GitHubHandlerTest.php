<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Espinoso\Handlers\GitHubHandler;

class GitHubHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function create_issue()
    {
        $github = new GitHubHandler;
        $updates = (object)[
            'message' => (object)['text' => 'espi issue blablatest']
        ];

        $this->assertTrue($github->shouldHandle($updates));
    }

    /**
     * @test
     */
    public function can_not_create_issue()
    {
        $github = new GitHubHandler;
        $updates = (object)[
            'message' => (object)['text' => 'not espi issue blablatest']
        ];

        $this->assertFalse($github->shouldHandle($updates));
    }
}
