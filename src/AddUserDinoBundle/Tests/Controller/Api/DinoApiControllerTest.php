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
     * jak odpalamy test
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
            'body' => json_encode($data)
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
            'body' => json_encode($data)
        ]);

        //testy jednostkowe
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $this->assertEquals('/app_test.php/api/dino/dalina',$response->getHeader('Location'));
        $finishedData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('age', $finishedData); //sprawdza czy array ma klucz health
        $this->assertEquals('DinApi', $data['name']);
    }

    /**
     *
     */
    public function testGETUser()
    {
        $this->createParameters(array(
            'health' => 13
        ));

        //GET to get user
        $response = $this->client->get('api/dino/ApiMail@ty.pl');

        //poniższy kod korzysta z klasy ReponseAsserter
        $this->asserter()->assertResponsePropertiesExist($response, array(
            'name',
            'email',
        ));
        $this->asserter()->assertResponsePropertyEquals($response, 'email', 'ApiMail@ty.pl');
    }


    public function testGETUserCollection()
    {
        $this->createUser('Apilianator@ty.pl');
        $this->createUser('Apility@ty.pl');

        //GET to get all users
        $response = $this->client->get('api/dino');

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyIsArray($response, 'users');
        $this->asserter()->assertResponsePropertyCount($response, 'users', 3); // 3 to liczba userów, dwaj stworzeni powyżej i jeden przy każdym teście
        $this->asserter()->assertResponsePropertyEquals($response, 'users[2].email', 'Apility@ty.pl');
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
            'body' => json_encode($data)
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
        $response = $this->client->delete('api/dino/Apikola@wp.pl');

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
            'body' => json_encode($data)
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
            'body' => json_encode($data)
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

    public function testUserPOSTErrors()
    {
        $data = array(
            'name' => 'Deliti',
            'email' => 'NewApi@rest.pl',
            'age' => 12,
//            'species' => 'Welociraptor',
            'plainPassword' => 'qwe' //mało bezpieczne
        );

        //1) POST to create user
        $response = $this->client->post('api/dino', [
            'body' => json_encode($data)
        ]);

        //testy jednostkowe, odc.1 course 2
        $this->assertEquals(400, $response->getStatusCode());
        $this->asserter()->assertResponsePropertiesExist($response, array(
            'type',
            'title',
            'errors'
        ));
        $this->asserter()->assertResponsePropertyExists($response, 'errors.species'); // sprawdza czy jest error pola health
        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'errors.species[0]', //[0] boć może być wiele errorów dla jednej właściwości
            'Każdy Dino należy do jakiegoś gatunku. Zmień to!'
        );
        $this->asserter()->assertResponsePropertyDoesNotExist($response, 'errors.email');
    }









}