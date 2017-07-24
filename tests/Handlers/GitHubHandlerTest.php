<?php

namespace Tests\Feature;

use Tests\Handlers\HandlersTestCase;
use App\Espinoso\Handlers\GitHubHandler;

class GitHubHandlerTest extends HandlersTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->handler = new GitHubHandler;
    }

    /**
     * @test
     */
    public function it_should_handle_when_receives_issue_command()
    {
        $update = $this->update(['text' => 'espi issue blablatest']);

        $this->assertTrue($this->handler->shouldHandle($update));
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        $update = $this->update(['text' => 'not espi issue blablatest']);

        $this->assertFalse($this->handler->shouldHandle($update));
    }
}
