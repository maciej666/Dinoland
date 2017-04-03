<?php

namespace AddUserDinoBundle\Tests\Controller\Blog;

use AddUserDinoBundle\Test\ApiTestCase;

class PostControllerTest extends ApiTestCase
{
    /** To co w funkcji setup, wykonuje się przy każdym odpaleniu testu */
    public function setup()
    {
        parent::setup();

        //metody z klasy ApiTestCase
        $user = $this->createUser('ApiMail@ty.pl');
        $this->createPosts($user);
    }


    public function testPOSTCreate()
    {
        //dane do stworzenia posta
        $data = array(
            'title' => 'Post stworzony przy pomocy API',
            'body' => 'Należy się uwierzytelnić aby dodać posta',
        );

        $response = $this->client->post('api/post/new', [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        //testy jednostkowe
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $finishedData = json_decode($response->getBody(), true);
        $this->assertEquals('/app_test.php/blog/post/'.$finishedData['slug'],$response->getHeader('Location'));
        $this->assertArrayHasKey('title', $finishedData); //sprawdza czy array ma klucz title
        $this->assertEquals('Post stworzony przy pomocy API', $data['title']);
    }


    public function testGETPost()
    {
        //wyszukuje posta po slugu
        $response = $this->client->get('api/post/wpis-o-samochodach');

        //poniższy kod korzysta z klasy ReponseAsserter
        $this->asserter()->assertResponsePropertiesExist($response, array(
            'title',
            'body',
        ));
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals(
            $response,
            '_links.self',
            $this->adjustUri('/api/post/wpis-o-samochodach')); //do każdego response'a z obiektem post dodawany jest link umożliwiający jego wyświetlenie
    }


    public function testGETPostsCollection()
    {
        $response = $this->client->get('api/posts');

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyIsArray($response, 'items');
    }


    public function testDELETEPost()
    {
        $response = $this->client->delete('api/post/delete/wpis-o-samochodach', [
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        $this->assertEquals(204, $response->getStatusCode()); //204 - success but without any content back
    }


    public function testPATCHPost()
    {
        $data = [
            'title' => 'Nowy temat'
        ];

        $response = $this->client->patch('api/post/edit/wpis-o-samochodach', [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals($response, 'title', 'Nowy temat');
        $this->asserter()->assertResponsePropertyEquals($response, 'body', 'To jest info o silnikach');//sprawdza czy pole body jest dalej takie samo
    }


}
