<?php
declare(strict_types=1);

namespace JelmerD\Bootstrap\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use JelmerD\Bootstrap\View\Helper\HtmlHelper;

/**
 * JelmerD\Bootstrap\View\Helper\HtmlHelper Test Case
 */
class HtmlHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \JelmerD\Bootstrap\View\Helper\HtmlHelper
     */
    protected $Html;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Html = new HtmlHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Html);

        parent::tearDown();
    }
}
