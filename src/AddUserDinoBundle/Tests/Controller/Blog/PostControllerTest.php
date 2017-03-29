<?php

namespace AddUserDinoBundle\Tests\Controller\Blog;

use AddUserDinoBundle\Test\ApiTestCase;

class PostControllerTest extends ApiTestCase
{
    /** To co w funkcji setup, wykonuje się przy każdym odpaleniu testu */
    public function setup()
    {
        parent::setup();

        $this->createUser('ApiMail@ty.pl');
    }


    public function testPOSTNew()
    {
        //dane do stworzenia posta
        $data = array(
            'title' => 'Post stworzony przy pomocy API',
            'body' => 'Należy się uwierzytelnić aby dodać posta',
        );

        //tworzenie tokena do autoryzacji; normalnie musimy otrzymać go od aplikacji
        $token = $this->getService('lexik_jwt_authentication.encoder')
            ->encode(['username' => 'ApiMail@ty.pl']); //tworzy token z nazwą usera który jest tworzony przy każdym teście

        $response = $this->client->post('api/new/post', [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        //testy jednostkowe
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $finishedData = json_decode($response->getBody(), true);
        $this->assertEquals('/app_test.php/blog/post/'.$finishedData['id'],$response->getHeader('Location'));
        $this->assertArrayHasKey('title', $finishedData); //sprawdza czy array ma klucz health
        $this->assertEquals('Post stworzony przy pomocy API', $data['title']);
    }


//    public function testGETPost()
//    {
//        //GET to get user
//        $response = $this->client->get('api/post/{id}/ApiMail@ty.pl'); //ApiMail@ty.pl user tworzony przy każdym teście
//
//        //poniższy kod korzysta z klasy ReponseAsserter
//        $this->asserter()->assertResponsePropertiesExist($response, array(
//            'name',
//            'email',
//        ));
//        $this->asserter()->assertResponsePropertyEquals($response, 'email', 'ApiMail@ty.pl');
//        $this->asserter()->assertResponsePropertyEquals(
//            $response,
//            '_links.self',
//            $this->adjustUri('/api/dino/ApiMail@ty.pl')); //sprawdza czy każdy User ma link do samego siebie, odc. 6 course 3
//    }

}
