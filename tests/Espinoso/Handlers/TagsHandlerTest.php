<?php namespace Tests\Espinoso\Handlers;

use App\Espinoso\Handlers\TagsHandler;

class TagsHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_receives_issue_command()
    {
        // Arrange
        $this->handler = $this->makeHandler();

        // Act & Assert
        $this->assertShouldHandle('espi #list cosa a guardar');
        $this->assertShouldHandle('#list cosa a guardar');

        $this->assertShouldHandle('espi ver #tag');
        $this->assertShouldHandle('espi show #list');
        $this->assertShouldHandle('list #tag');
        $this->assertShouldHandle('listar #list');

        $this->assertShouldHandle('espi tags');
        $this->assertShouldHandle('tags');

        $this->assertShouldHandle('espi clean #tag');
        $this->assertShouldHandle('espi clear #list');
        $this->assertShouldHandle('limpiar #tag');
        $this->assertShouldHandle('vaciar #list');
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        // Arrange
        $this->handler = $this->makeHandler();

        // Act & Assert
        $this->assertShouldNotHandle('espi ver tag');
        $this->assertShouldNotHandle('espi limpiar tag');
    }

    /**
     * @return TagsHandler
     */
    protected function makeHandler(): TagsHandler
    {
        return new TagsHandler($this->espinoso);
    }
}
