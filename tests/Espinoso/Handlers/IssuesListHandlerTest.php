<?php

namespace Tests\Espinoso\Handlers;


use App\Espinoso\Handlers\IssuesListHandler;
use App\Facades\GuzzleClient;
use Mockery;
use Symfony\Component\DomCrawler\Crawler;

class IssuesListHandlerTest extends HandlersTestCase
{

    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'issues list']),
            $this->makeMessage(['text' => 'issue list']),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertTrue($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        // Arrange
        $handler = $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'issues']),
            $this->makeMessage(['text' => 'issue']),
            $this->makeMessage(['text' => 'listss issues']),
            $this->makeMessage(['text' => 'lista issues']),
            $this->makeMessage(['text' => 'issuess lista']),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertFalse($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_handle_and_return_info()
    {

        $jsonText = '[
  {
    "url": "https://api.github.com/repos/12-cactus/espinoso/issues/103",
    "repository_url": "https://api.github.com/repos/12-cactus/espinoso",
    "labels_url": "https://api.github.com/repos/12-cactus/espinoso/issues/103/labels{/name}",
    "comments_url": "https://api.github.com/repos/12-cactus/espinoso/issues/103/comments",
    "events_url": "https://api.github.com/repos/12-cactus/espinoso/issues/103/events",
    "html_url": "https://github.com/12-cactus/espinoso/issues/103",
    "id": 286332165,
    "number": 103,
    "title": "Hacer Handler con el GSM",
    "user": {
      "login": "marivgil",
      "id": 11651657,
      "avatar_url": "https://avatars0.githubusercontent.com/u/11651657?v=4",
      "gravatar_id": "",
      "url": "https://api.github.com/users/marivgil",
      "html_url": "https://github.com/marivgil",
      "followers_url": "https://api.github.com/users/marivgil/followers",
      "following_url": "https://api.github.com/users/marivgil/following{/other_user}",
      "gists_url": "https://api.github.com/users/marivgil/gists{/gist_id}",
      "starred_url": "https://api.github.com/users/marivgil/starred{/owner}{/repo}",
      "subscriptions_url": "https://api.github.com/users/marivgil/subscriptions",
      "organizations_url": "https://api.github.com/users/marivgil/orgs",
      "repos_url": "https://api.github.com/users/marivgil/repos",
      "events_url": "https://api.github.com/users/marivgil/events{/privacy}",
      "received_events_url": "https://api.github.com/users/marivgil/received_events",
      "type": "User",
      "site_admin": false
    },
    "labels": [],
    "state": "open",
    "locked": false,
    "assignee": null,
    "assignees": [],
    "milestone": null,
    "comments": 0,
    "created_at": "2018-01-05T16:11:22Z",
    "updated_at": "2018-01-05T16:11:22Z",
    "closed_at": null,
    "author_association": "CONTRIBUTOR",
    "body": ""
  }
  ]';

        $crawler = Mockery::mock(Crawler::class);
        $crawler->shouldReceive('getBody')->andReturnSelf();
        $crawler->shouldReceive('getContents')->andReturn($jsonText);

        GuzzleClient::shouldReceive('request')
            ->withArgs(['GET', config('espinoso.url.issues')])
            ->andReturn($crawler);

        $text = " - 103 - Hacer Handler con el GSM
 https://github.com/12-cactus/espinoso/issues/103
";

        $this->espinoso->shouldReceive('reply')->once()->with($text);

        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'issues list']);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }

    protected function makeHandler(): IssuesListHandler
    {
        return new IssuesListHandler($this->espinoso);
    }

}