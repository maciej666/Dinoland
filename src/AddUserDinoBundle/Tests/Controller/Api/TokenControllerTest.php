<?php

namespace AddUserDinoBundle\Tests\Controller\Api;

use AddUserDinoBundle\Test\ApiTestCase;

class TokenControllerTest extends ApiTestCase
{
    public function testPOSTCreateToken()
    {
        $this->createUser('ApiToken@wp.pl', 'secretPass000');

        $response = $this->client->post('api/tokens',[
            'auth' => ['ApiToken@wp.pl', 'secretPass000'] //używa HTTP Basic authentication. Guzzle używa do tego opcji auth. W produkcji należy użyć HTTPS
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyExists(
            $response,
            'token'
        );
    }


    public function testPOSTInvalidCredential()
    {
        $this->createUser('ApiToken2@wp.pl', 'secretPass666');

        $response = $this->client->post('api/tokens',[
            'auth' => ['ApiToken2@wp.pl', 'BADsecretPass000'] //używa HTTP Basic authentication. Guzzle używa do tego opcji auth. W produkcji należy użyć HTTPS
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

}