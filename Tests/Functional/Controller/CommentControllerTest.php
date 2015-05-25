<?php

namespace ARV\BlogBundle\Tests\Functional\Controller;


use ARV\BlogBundle\Tests\Functional\AbstractFunctionalTest;

/**
 * Class CommentControllerTest
 * @package ARV\BlogBundle\Tests\Controller
 */
class CommentControllerTest extends AbstractFunctionalTest
{

    /**
     * @var
     */
    private $manager;
    /**
     * @var
     */
    private $articleManager;
    /**
     * @var
     */
    private $url;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->container->get('arv_blog_manager_comment');
        $this->articleManager = $this->container->get('arv_blog_manager_article');
        $this->url = '/commentaire';
    }

    /**
     *
     */
    public function test_comment_manage()
    {
        $this->client->request('GET', $this->url . '/admin');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function test_comment_list()
    {
        $this->client->request('GET', $this->url . '/liste');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function test_comment_list_for_article()
    {
        $article = $this->articleManager->getRepository()->findOneByTitle('Lorem Ipsum again');
        $this->client->request('GET', $this->url . '/liste/' . $article->getId());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function test_comment_show()
    {
        $comment = $this->manager->getRepository()->findOneByEmail('user@gmail.com');
        $this->client->request('GET', $this->url . '/' . $comment->getId());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function test_comment_create_ok()
    {
        // First creation : no problem
        $count = $this->countComments();
        $crawler = $this->client->request('GET', $this->url . '/nouveau');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_comment_submit')->form(array(
            'arv_blogbundle_comment[email]' => 'user@gmail.com',
            'arv_blogbundle_comment[content]' => 'Edit comment'
        ));
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count + 1, $this->countComments());

        // Second creation : error
        $crawler = $this->client->request('GET', $this->url . '/nouveau');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_comment_submit')->form(array(
            'arv_blogbundle_comment[email]' => 'user@gmail.com',
            'arv_blogbundle_comment[content]' => 'Edit comment'
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count + 1, $this->countComments());
    }

    /**
     *
     */
    public function test_comment_create_ko()
    {
        $count = $this->countComments();
        $crawler = $this->client->request('GET', $this->url . '/nouveau');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_comment_submit')->form(array(
            'arv_blogbundle_comment[_token]' => ''
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countComments());
    }

    /**
     *
     */
    public function test_comment_create_for_article()
    {
        $count = $this->countComments();
        $article = $this->articleManager->getRepository()->findOneByTitle('Lorem Ipsum again');
        $crawler = $this->client->request('GET', $this->url . '/nouveau/' . $article->getId());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_comment_submit')->form(array(
            'arv_blogbundle_comment[email]' => 'user@gmail.com',
            'arv_blogbundle_comment[content]' => 'Edit comment'
        ));
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count + 1, $this->countComments());
    }

    /**
     *
     */
    public function test_comment_edit_ok()
    {
        $count = $this->countComments();
        $comment = $this->manager->getRepository()->findOneByEmail('user@gmail.com');
        $crawler = $this->client->request('GET', $this->url . '/' . $comment->getId() . '/modifier');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_comment_submit')->form(array(
            'arv_blogbundle_comment[email]' => 'user@gmail.com',
            'arv_blogbundle_comment[content]' => 'Edit comment'
        ));
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countComments());
    }

    /**
     *
     */
    public function test_comment_edit_ko()
    {
        $count = $this->countComments();
        $comment = $this->manager->getRepository()->findOneByEmail('user@gmail.com');
        $crawler = $this->client->request('GET', $this->url . '/' . $comment->getId() . '/modifier');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_comment_submit')->form(array(
            'arv_blogbundle_comment[_token]' => ''
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countComments());
    }

    /**
     *
     */
    public function test_comment_delete_ok()
    {
        $count = $this->countComments();
        $comment = $this->manager->getRepository()->findOneByEmail('user@gmail.com');
        $crawler = $this->client->request('GET', $this->url . '/' . $comment->getId());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('form_submit')->form(array());
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count - 1, $this->countComments());
    }

    /**
     *
     */
    public function test_comment_delete_ko()
    {
        $count = $this->countComments();
        $comment = $this->manager->getRepository()->findOneByEmail('user@gmail.com');
        $crawler = $this->client->request('GET', $this->url . '/' . $comment->getId());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('form_submit')->form(array(
            'form[_token]' => ''
        ));
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countComments());
    }

    /**
     * @return mixed
     */
    private function countComments()
    {
        return $this->manager->count();
    }

}

