<?php
declare(strict_types=1);

namespace JelmerD\Bootstrap\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use JelmerD\Bootstrap\View\Helper\TableHelper;

/**
 * JelmerD\Bootstrap\View\Helper\TableHelper Test Case
 */
class TableHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \JelmerD\Bootstrap\View\Helper\TableHelper
     */
    protected $Table;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Table = new TableHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Table);

        parent::tearDown();
    }
}
