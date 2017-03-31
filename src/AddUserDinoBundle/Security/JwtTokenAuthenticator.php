<?php

namespace AddUserDinoBundle\Security;

use AddUserDinoBundle\Api\ApiProblem;
use AddUserDinoBundle\Api\ResponseFactory;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator; //symfonowy authenticator

/** Podobna klasa znajduje się w bandlu lexik jwt ver. 2.0; Patrz odc. 07 course 4 */
class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;


    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;


    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManager $em, ResponseFactory $responseFactory)
    {

        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
        $this->responseFactory = $responseFactory;
    }

    /** Metody odpalają się jedna za drugą */
    public function getCredentials(Request $request)
    {
        //łapie token przesłanego w nagłówku authorization, patrz DinoApiControllerTest testPOSTUser()
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        //zaprzestanie autentykacji za pomocą tej metody
        if (!$token) {
            return null;
        }

        return $token;
    }


    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //Decode'owanie tokena i wyszukiwanie usera który został przesłany wraz z tokenem
        $data = $this->jwtEncoder->decode($credentials); //credentials to nasz token. $data to teraz array tego co wrzuciliśmy do tokena. Encoder sprawdza czy zawartość token'a nie została zmieniona korzystając z naszego private key oraz czy token jest dalej aktualny

        if ($data === false) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }

        $username = $data['username'];

        //gdy nie ma usera zwraca null i kończy
        return $this->em
            ->getRepository('AddUserDinoBundle:User')
            ->findOneByUsername($username);
    }


    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // getUser nie zwróci Usera gdy skończyła się ważność Tokena, bądź gdy jakimś cudem nie ma usera w bazie danych
        $apiProblem = new ApiProblem(401);
        $apiProblem->set('detail', $exception->getMessageKey());

        return $this->responseFactory->createResponse($apiProblem);
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //Tu nic boć chcemy aby dalej wykonywał się kod z controllera
    }


    public function supportsRememberMe()
    {
        return false; //nie dotyczy api
    }


    public function start(Request $request, AuthenticationException $authException = null)
    {
        //Wywala błąd gdy nie wyślemy poprawnego nagłówka authorization
        $apiProblem = new ApiProblem(401);

        //W przypadku gdy nie ma żadnego info o błedzie
        $message = $authException ? $authException->getMessageKey() : 'Missing credentials';
        $apiProblem->set('detail', $message);

        return $this->responseFactory->createResponse($apiProblem);
    }


}