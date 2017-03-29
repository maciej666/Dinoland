<?php

namespace AddUserDinoBundle\Test;

use AddUserDinoBundle\Entity\DinoParameters;
use AddUserDinoBundle\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Subscriber\History;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\PropertyAccess\Exception\RuntimeException;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Klasa pozwala na debugowanie Api i wypełnia danymi tabele na potrzeby testów.
 * Stworzona przez knpUniveristy. Kurs Symfony RESTfull API Course 1
 * @package AddUserDinoBundle\Test
 */
class ApiTestCase extends KernelTestCase
{
    private static $staticClient;

    /**
     * @var History
     */
    private static $history;

    /**
     * @var Client
     */
    protected $client;

    private $output;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * Na potrzeby dostępu do klasy ResponseAsserter
     * @var
     */
    private $responseAsserter;

    public static function setUpBeforeClass()
    {
        //TEST_BASE_URL zdefiniowany w phpunit.xml.dist
        $baseUrl = getenv('TEST_BASE_URL');
        self::$staticClient = new Client([
            'base_url' => $baseUrl,
            'defaults' => [
                'exceptions' => false
            ]
        ]);
        self::$history = new History();
        self::$staticClient->getEmitter()
            ->attach(self::$history);
        self::bootKernel();
    }


    protected function setUp()
    {
        $this->client = self::$staticClient;
        $this->purgeDatabase();
    }


    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {
        // purposefully not calling parent class, which shuts down the kernel
    }


    /**
     * Automatically prints the last response on a failure
     */
    protected function onNotSuccessfulTest(Exception $e)
    {
        if (self::$history && $lastResponse = self::$history->getLastResponse()) {
            $lastRequest = self::$history->getLastRequest();
            $this->printDebug('');
            $this->printDebug('<error>Failure!</error> when making the following request:');
            $this->printDebug(sprintf('<comment>%s</comment>: <info>%s</info>', $lastRequest->getMethod(), $lastRequest->getUrl())."\n");
            $this->debugResponse($lastResponse);
        }
        throw $e;
    }


    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getService('doctrine.orm.default_entity_manager'));
        $purger->purge();
    }


    protected function getService($id)
    {
        return self::$kernel->getContainer()
            ->get($id);
    }


    /**
     * Prints the given response to the screen in a nice debug way
     */
    protected function debugResponse(ResponseInterface $response)
    {
        $body = (string) $response->getBody();
        $contentType = $response->getHeader('Content-Type');
        if ($contentType == 'application/json' || strpos($contentType, '+json') !== false) {
            $data = json_decode($body);
            if ($data === null) {
                // invalid JSON!
                $this->printDebug($body);
            } else {
                // valid JSON, print it pretty
                $this->printDebug(json_encode($data, JSON_PRETTY_PRINT));
            }
        } else {
            // the response is HTML - see if we should print all of it or some of it
            $isValidHtml = strpos($body, '</body>') !== false;
            if ($isValidHtml) {
                $this->printDebug('<error>Failure!</error> Below is a summary of the HTML response from the server.');
                // finds the h1 and h2 tags and prints them only
                $crawler = new Crawler($body);
                $i = 1;
                foreach ($crawler->filter('h1, h2')->extract(array('_text')) as $header) {
                    $this->printDebug('');
                    $this->printDebug(sprintf(
                        '  <comment>%s)</comment> %s',
                        $i,
                        trim($header)
                    ));
                    $i++;
                }
            } else {
                $this->printDebug($body);
            }
        }
    }


    protected function printDebug($string)
    {
        if ($this->output === null) {
            $this->output = new ConsoleOutput();
        }
        echo $string;
    }


    protected function assertResponsePropertiesExist(ResponseInterface $response, array $expectedProperties)
    {
        foreach ($expectedProperties as $propertyPath) {
            // this will blow up if the property doesn't exist
            $this->readResponseProperty($response, $propertyPath);
        }
    }


    protected function assertResponsePropertyExists(ResponseInterface $response, $propertyPath)
    {
        // this will blow up if the property doesn't exist
        $this->readResponseProperty($response, $propertyPath);
    }


    protected function assertResponsePropertyDoesNotExist(ResponseInterface $response, $propertyPath)
    {
        try {
            // this will blow up if the property doesn't exist
            $this->readResponseProperty($response, $propertyPath);
            $this->fail(sprintf('Property "%s" exists, but it should not', $propertyPath));
        } catch (RuntimeException $e) {
            // cool, it blew up
            // this catches all errors (but only errors) fro, the PropertyAccess component
        }
    }


    protected function assertResponsePropertyEquals(ResponseInterface $response, $propertyPath, $expectedValue)
    {
        $actual = $this->readResponseProperty($response, $propertyPath);
        $this->assertEquals(
            $expectedValue,
            $actual,
            sprintf(
                'Property "%s": Expected "%s" but response was "%s"',
                $propertyPath,
                $expectedValue,
                var_export($actual, true)
            )
        );
    }


    protected function assertResponsePropertyContains(ResponseInterface $response, $propertyPath, $expectedValue)
    {
        $actualPropertValue = $this->readResponseProperty($response, $propertyPath);
        $this->assertContains(
            $expectedValue,
            $actualPropertValue,
            sprintf(
                'Property "%s": Expected to contain "%s" but response was "%s"',
                $propertyPath,
                $expectedValue,
                var_export($actualPropertValue, true)
            )
        );
    }


    protected function assertResponsePropertyIsArray(ResponseInterface $response, $propertyPath)
    {
        $this->assertInternalType('array', $this->readResponseProperty($response, $propertyPath));
    }


    protected function assertResponsePropertyCount(ResponseInterface $response, $propertyPath, $expectedCount)
    {
        $this->assertCount((int) $expectedCount, $this->readResponseProperty($response, $propertyPath));
    }


    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getService('doctrine')
            ->getManager();
    }


    private function readResponseProperty(ResponseInterface $response, $propertyPath)
    {
        if ($this->accessor === null) {
            $this->accessor = PropertyAccess::createPropertyAccessor();
        }
        $data = json_decode((string)$response->getBody());
        try {
            return $this->accessor->getValue($data, $propertyPath);
        } catch (AccessException $e) {
            // it could be a stdClass or an array of stdClass
            $values = is_array($data) ? $data : get_object_vars($data);
            throw new AccessException(sprintf(
                'Error reading property "%s" from available keys (%s)',
                $propertyPath,
                implode(', ', array_keys($values))
            ), 0, $e);
        }
    }


    protected function createUser($username, $plainPassword = "qwe")
    {
        $user = new User();
        $user->setEmail($username);
        $user->setAge(12);
        $user->setName('Dapi'.rand(0,1000000));
        $user->setSpecies('Diplodok');
        $user->setEnabled(1);
        $pass = $this->getService('security.password_encoder')
            ->encodePassword($user, $plainPassword);
        $user->setPassword($pass);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }


    /**
     * Tworzy DinoParameters w relacji do istniejącego Usera
     * @param array $data
     */
    protected function createParameters(array $data, $email = 'ApiMail@ty.pl')
    {
        //skleja dwie tablice
        $data = array_merge(array(
            'health' => rand(0,100),
            'strength' => rand(0,100),
            'speed' => rand(0,100),
            'backup' => rand(0,100),
            'createdAt' => new \DateTime(),
        ), $data);

        //PropertyAccess działą przy form component, wywołuje np. setttersy i gettersy
        $accessor = PropertyAccess::createPropertyAccessor();
        $parameters = new DinoParameters();
        foreach($data as $key => $value) {
            $accessor->setValue($parameters, $key, $value);
        }

        //tworzenie relacji
        $user = $this->getEntityManager()
            ->getRepository('AddUserDinoBundle:User')
            ->findOneByEmail($email);
        $user->setDino($parameters);

        $this->getEntityManager()->persist($parameters);
        $this->getEntityManager()->flush();

        return $parameters;
    }

    /**
     * Udostępnia metody klasy ResponseAsserter
     * @return ResponseAsserter
     */
    protected function asserter()
    {
        if($this->responseAsserter === null  ){
            $this->responseAsserter = new ResponseAsserter();
        }

        return $this->responseAsserter;
    }


    /**
     * Poprzedza uri /app_test.php na potrzeby testów
     * boć poprzedza też w setUpBeforeClass() przy tworzeniu klienta
     * @param $uri
     * @return string
     */
    public function adjustUri($uri)
    {
        return '/app_test.php'.$uri;
    }


    protected function getAuthorizedHeaders($username, $headers = array())
    {
        //tworzenie tokena do autoryzacji
        $token = $this->getService('lexik_jwt_authentication.encoder')
            ->encode(['username' => $username]); //tworzy nowego usera w oparciu o token starego test:/

        $headers['Authorization'] = 'Bearer '.$token;

        return $headers;
    }
}