<?php
declare(strict_types=1);

namespace JelmerD\Bootstrap\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use JelmerD\Bootstrap\View\Helper\PaginatorHelper;

/**
 * JelmerD\Bootstrap\View\Helper\PaginatorHelper Test Case
 */
class PaginatorHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \JelmerD\Bootstrap\View\Helper\PaginatorHelper
     */
    protected $Paginator;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Paginator = new PaginatorHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Paginator);

        parent::tearDown();
    }
}
