<?php

namespace AddUserDinoBundle\Tests\Controller\Api;

use AddUserDinoBundle\Test\ApiTestCase;

/**
 * Testy Api z DinoApiController.
 * @package AddUserDinoBundle\Tests\Controller\Api
 */
class DinoApiControllerTest extends ApiTestCase
{
    /**
     * To co w funkcji setup, wykonuje się przy każdym odpaleniu testu
     */
    public function setup()
    {
        parent::setup();

        $this->createUser('ApiMail@ty.pl');
    }


    public function testPOSTParameters()
    {
        //przykładowe dane wysłane przez usera
        $data = array(
            'health' => 666,
            'backup' => rand(1,1000),
            'speed' => rand(1,1000),
            'strength' => rand(1,1000),
        );

        //1) POST to create parameters
        $response = $this->client->post('api/dino/parameters', [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        //testy jednostkowe
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $this->assertEquals('/app_test.php/api/dino/dalina',$response->getHeader('Location'));
        $finishedData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('health', $finishedData); //sprawdza czy array ma klucz health
        $this->assertEquals('666', $data['health']);
    }


    public function testPOSTUser()
    {
        $data = array(
            'name' => 'DinApi',
            'email' => 'NewApi@rest.pl',
            'age' => 12,
            'species' => 'Welociraptor',
            'plainPassword' => 'qwe' //mało bezpieczne
        );

        //1) POST to create user
        $response = $this->client->post('api/dino', [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        //testy jednostkowe
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $this->assertEquals('/app_test.php/api/dino/dalina',$response->getHeader('Location'));
        $finishedData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('age', $finishedData); //sprawdza czy array ma klucz health
        $this->assertEquals('DinApi', $data['name']);
    }


    public function testGETUser()
    {
        //GET to get user
        $response = $this->client->get('api/dino/ApiMail@ty.pl', [
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]); //ApiMail@ty.pl user tworzony przy każdym teście

        //poniższy kod korzysta z klasy ReponseAsserter
        $this->asserter()->assertResponsePropertiesExist($response, array(
            'name',
            'email',
        ));
        $this->asserter()->assertResponsePropertyEquals($response, 'email', 'ApiMail@ty.pl');
        $this->asserter()->assertResponsePropertyEquals(
            $response,
            '_links.self',
            $this->adjustUri('/api/dino/ApiMail@ty.pl')); //sprawdza czy każdy User ma link do samego siebie, odc. 6 course 3
    }


    public function testGETUserCollection()
    {
        $this->createUser('Apilianator@ty.pl');
        $this->createUser('Apility@ty.pl');

        //GET to get all users
        $response = $this->client->get('api/dino', [
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyIsArray($response, 'items');
        $this->asserter()->assertResponsePropertyCount($response, 'items', 3); // 3 to liczba userów, dwaj stworzeni powyżej i jeden przy każdym teście
        $this->asserter()->assertResponsePropertyEquals($response, 'items[2].email', 'Apility@ty.pl');
    }


    public function testPUTUser()
    {
        $this->createUser('Apiniati@ty.pl');

        //Aktualizacja danych; przy PUT trzeba aktualizować cały zasób.
        //Przechodzi testy nawet jak nie ma wszystkich danych, a nie powinno!?
        $data = array(
            'name' => 'DinApi',
            'email' => 'NewApi@rest.pl',
            'age' => 12,
            'species' => 'Welociraptor',
            'plainPassword' => 'qwe'
        );

        //PUT to update(replece) user
        $response = $this->client->put('api/dino/Apiniati@ty.pl', array(
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ));

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals($response, 'name', 'DinApi');
        //Nasz dino może być zmieniony tylko przy tworzeniu odc. 15
        $this->asserter()->assertResponsePropertyEquals($response, 'species', 'Diplodok');//REST course 1 odc. 15 - jak zablokować możliwość edycji pola
    }


    public function testDELETEUser()
    {
        $this->createUser('Apikola@wp.pl');

        //DELETE to delete user
        $response = $this->client->delete('api/dino/Apikola@wp.pl', [
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        $this->assertEquals(204, $response->getStatusCode()); //204 - success but without any content back
    }


    public function testPATCHUser()
    {
        $this->createUser('Apiniati@ty.pl');

        //Aktualizacja danych; przy PUT trzeba aktualizować cały zasób.
        $data = array(
            'email' => 'patchApi@rest.pl',
        );

        //PATCH to update field in user
        $response = $this->client->patch('api/dino/Apiniati@ty.pl', array(
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ));

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals($response, 'email', 'patchApi@rest.pl');
        $this->asserter()->assertResponsePropertyEquals($response, 'age', 12); //badaj zależnośc testu od opcji disabled w formularzu
        //Nasz dino może być zmieniony tylko przy tworzeniu odc. 15
        $this->asserter()->assertResponsePropertyEquals($response, 'species', 'Diplodok');//sprawdza czy pole Diplodok dalej takie samo
    }


    public function testValidationErrors()
    {
        //przykładowe dane wysłane przez usera
        $data = array(
            //brak health
            'backup' => rand(1,1000),
            'speed' => rand(1,1000),
            'strength' => rand(1,1000),
        );

        //1) POST to create parameters
        $response = $this->client->post('api/dino/parameters', [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        //testy jednostkowe, odc.1 course 2
        $this->assertEquals(400, $response->getStatusCode());
        $this->asserter()->assertResponsePropertiesExist($response, array(
            'type',
            'title',
            'errors'
        ));
        $this->asserter()->assertResponsePropertyExists($response, 'errors.health'); // sprawdza czy jest error pola health
        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'errors.health[0]', //[0] boć może być wiele errorów dla jednej właściwości
            'Dino bez zdrowia nie żyje!'
        );
        $this->asserter()->assertResponsePropertyDoesNotExist($response, 'errors.speed');
        $this->assertEquals('application/problem+json', $response->getHeader('Content-Type'));
    }


    /** Nie działa gdyż POST sprawdza token - do poprawy !!!!!*/
    public function testUserPOSTErrors()
    {
        $data = array(
            'name' => 'Deliti',
//            'email' => 'NewApi@rest.pl',
            'age' => 12,
            'species' => 'Welociraptor',
            'plainPassword' => 'qwe' //mało bezpieczne
        );

        //1) POST to create user
        $response = $this->client->post('api/dino', [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        //testy jednostkowe, odc.1 course 2
        $this->assertEquals(400, $response->getStatusCode());
        $this->asserter()->assertResponsePropertiesExist($response, array(
            'type',
            'title',
            'errors'
        ));
        $this->asserter()->assertResponsePropertyExists($response, 'errors.email'); // sprawdza czy jest error pola health
        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'errors.email[0]', //[0] boć może być wiele errorów dla jednej właściwości
            'Proszę podać adres email.'
        );
        $this->asserter()->assertResponsePropertyDoesNotExist($response, 'errors.age');
    }

    public function testnvalidJson()
    {
        $invalidJson = <<<EOF
{
            "health" : "3
            "speed" : "23
}
EOF;
        //1) POST to create parameters
        $response = $this->client->post('api/dino/parameters', [
            'body' => $invalidJson,
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        //odc.7 course 2
        $this->assertEquals(400, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyContains(
            $response,
            'type',
            'invalid_body_format'
        );
    }


    public function test404Exception()
    {
        $response = $this->client->post('api/fake',[
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        $this->assertEquals(404, $response->getStatusCode()); // 404 - Not Found
        $this->assertEquals('application/problem+json', $response->getHeader('Content-Type'));
        $this->asserter()->assertResponsePropertyContains(
            $response,
            'type',
            'about:blank' // według rekomendacji Scrap'a. Jeżeli kod (tu 404) mówi sam za siebie zostawiamy about:blank a w title standardowy opis słowny błedu 404(tu Not Found)
        );
        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'title',
            'Not Found' // według rekomendacji Scrap'a. Jeżeli kod (tu 404) mówi sam za siebie zostawiamy about:blank a w title opis słowny 404(tu Not Found)
        );
        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'detail', //komunikat zrozumiały dla użytkownika strony
            'No route found for "POST /api/fake"'
        );
    }


    public function testGETUsersCollectionPaginated()
    {
        $this->createUser('NotMatchingEmail@wp.pl');
        for ($i = 0; $i < 30; $i++) {
            $this->createUser('Apilianator'.$i.'@ty.pl');
        }

        //GET to get all users
        $response = $this->client->get('api/dino?filter=Apilianator', [
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertResponsePropertyExists($response, 'items');
        $this->assertResponsePropertyExists($response, '_links');
        $this->asserter()->assertResponsePropertyEquals($response, 'items[5].email', 'Apilianator5@ty.pl');
        $this->asserter()->assertResponsePropertyEquals($response, 'count', 10);
        $this->asserter()->assertResponsePropertyEquals($response, 'total', 30); // +1 tworzony przy każdym teście, jak odpalono filtr to już nie ma +1
        $this->asserter()->assertResponsePropertyExists($response, '_links.next'); //linki do następnych stron; _links.next oznacza że next jest osadzony w _links (tak gdyż linki nie dotyczą bezpośrednio usera)

        $nextUrl = $this->asserter()->readResponseProperty($response, '_links.next');
        $response = $this->client->get($nextUrl, [
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals($response, 'items[5].email', 'Apilianator15@ty.pl');
        $this->asserter()->assertResponsePropertyEquals($response, 'count', 10);

        $lastUrl = $this->asserter()->readResponseProperty($response, '_links.last');
        $response = $this->client->get($lastUrl, [
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals($response, 'items[0].email', 'Apilianator20@ty.pl');
        $this->asserter()->assertResponsePropertyEquals($response, 'count', 10);
        $this->asserter()->assertResponsePropertyDoesNotExist($response, 'items[10].email');
    }


    public function testGETUserDeep()
    {
        $user = $this->createUser('ApiRalation@ty.pl');
        $data = array();
        $parameters = $this->createParameters($data, 'ApiRalation@ty.pl');
        $user->setDino($parameters);

        //Jeżeli deep = 1 chcemy zwrócić obiekt user'a wraz z wszelkimi obiektami które pozostają do niego w relacji
        $response = $this->client->get('api/dino/ApiRalation@ty.pl?deep=1', [
            'headers' => [
                'Authorization' => $this->getAuthorizedHeaders('ApiMail@ty.pl')
            ]
        ]); //grupa deep sprawdzana w metodzie serialize() DinoApiController a tworzona w encji User

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyExists(
            $response,
            'dino.speed' //dino to parametry dina
        );
    }


    public function testRequiresAuthentication()
    {
        $response = $this->client->post('api/dino', [
            'body' => '[]'
        ]);
        $this->assertEquals(401, $response->getStatusCode());//401 - unauthorized

    }







    }