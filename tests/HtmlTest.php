<?php
require_once __DIR__ . '/../src/Ultra/Html/Html.php';

class HtmlTest extends PHPUnit_Framework_TestCase
{
    private $html;

    public function setUp() {
        $this->html = new Ultra\Html\Html;
    }

    public function testLink() {
        $link = $this->html->link('/test', 'my link');
        $this->assertEquals($link, '<a href="/test" >my link</a>');

        $link = $this->html->link('test', 'my link');
        $this->assertEquals($link, '<a href="/test" >my link</a>');

        $other_html = new Ultra\Html\Html(array('base_uri' => '/test'));
        $link = $other_html->link('articles');
        $this->assertEquals('<a href="/test/articles" >/test/articles</a>', $link);
    }

    public function testList() {
        $list = $this->html->ul(array('item 1', 'item 2'));
        $this->assertEquals('<ul><li>item 1</li><li>item 2</li></ul>', $list);

        $list = $this->html->ul(array('item 1', array('item 2')));
        $this->assertEquals('<ul><li>item 1</li><li><ul><li>item 2</li></ul></li></ul>', $list);

        $list = $this->html->ol(array('item 1', array('item 2')));
        $this->assertEquals('<ol><li>item 1</li><li><ol><li>item 2</li></ol></li></ol>', $list);
    }
}

