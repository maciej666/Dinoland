<?php

namespace AddUserDinoBundle\Security;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator; //symfonowy authenticator

/** Patrz odc. 07 course 4 */
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


    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManager $em)
    {

        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
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
        // TODO: Implement onAuthenticationFailure() method.
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }


    public function supportsRememberMe()
    {
        return false; //nie dotyczy nas
    }


    public function start(Request $request, AuthenticationException $authException = null)
    {
        // TODO: Implement start() method.
    }


}