<?php
require_once __DIR__ . '/../src/Ultra/Html/Form.php';

class FormTest extends PHPUnit_Framework_TestCase
{
    private $form;

    public function setUp() {
        $this->form = new Ultra\Html\Form;
    }

    public function testOpen() {
        $form = $this->form;

        $open = $form->open('post', '/test', false);
        $this->assertEquals('<form method="post" action="/test" >', $open);

        $_SERVER['REQUEST_URI'] = '/test-uri';
        $open = $form->open('post', null, false);
        $this->assertEquals('<form method="post" action="/test-uri" >', $open);

        $open = $form->open('post', '/test', true);
        $this->assertEquals('<form method="post" action="/test" enctype="multipart/form-data">', $open);

        $other_form = new Ultra\Html\Form(array('base_uri' => '/test'));
        $open = $other_form->open('post', 'articles', false);
        $this->assertEquals('<form method="post" action="/test/articles" >', $open);
    }

    public function testInput() {
        $form = $this->form;

        $this->assertNotNull($form);

        // test simple attributes
        $input = $form->input('test-name', 'text', array('placeholder' => 'hello!', 'value' => '123'));
        $this->assertContains('name="test-name"', $input);
        $this->assertContains('placeholder="hello!"', $input);
        $this->assertContains('type="text"', $input);
        $this->assertContains('value="123"', $input);
    }

    public function testPassword() {
        $form = $this->form;

        $input = $form->password('pass');
        $this->assertContains('type="password"', $input);
        $this->assertContains('name="pass"', $input);
    }

    public function testLabel() {
        $form = $this->form;

        $label = $form->label('Name:', 'name');
        $this->assertEquals('<label for="name" >Name:</label>', $label);


        $label = $form->label('Name:', 'name', array('id' => 'test-id'));
        $this->assertContains('id="test-id"', $label);
    }

    public function testCsrf() {
        $form = $this->form;

        $_SERVER['REQUEST_URI'] = 'http://localhost/test';

        $form->open();
        $form_close = $form->close();

        $this->assertContains('name="__uf_token"', $form_close);

        // Test submitting a form
        $_REQUEST['__uf_token'] = $form->get_csrf_token();

        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['HTTP_REFERER'] = 'http://localhost/some_page';
        $this->assertTrue($form->is_safe());
        // token should be invalid by now, so it wont work more than once
        $this->assertFalse($form->is_safe());
    }

    public function testSubmit() {
        $form = $this->form;

        $btn = $form->submit();
        $this->assertEquals('<button type="submit" name="submit">Submit</button>', $btn);

        $btn = $form->submit('btn-text');
        $this->assertEquals('<button type="submit" name="btn-text">Submit</button>', $btn);

        $btn = $form->submit('my-btn', 'btn', array('id' => 2));
        $this->assertContains('id="2"', $btn);
    }

    public function testTextarea() {
        $form = $this->form;

        $ta = $form->textarea('ta_text');
        $this->assertContains('name="ta_text"', $ta);


        $ta = $form->textarea('ta_text', 'my text', array('id' => 1));
        $this->assertContains('>my text<', $ta);
        $this->assertContains('id="1"', $ta);
    }

    public function testSelect() {
        $form = $this->form;

        $select = $form->select('test-select', array('mike', 'john', 'maria', 'alice'), array('id' => 2, 'custom-attr' => 'one'));

        $this->assertContains('name="test-select"', $select);
        $this->assertContains('<option value="0">mike</option>', $select);
        $this->assertContains('<option value="3">alice</option>', $select);
        $this->assertContains('id="2" custom-attr="one"', $select);
    }
}
