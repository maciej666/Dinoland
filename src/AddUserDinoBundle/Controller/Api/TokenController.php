<?php

namespace AddUserDinoBundle\Controller\Api;

use AddUserDinoBundle\Controller\Api;
use AddUserDinoBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TokenController extends BaseController
{
    /**
     * @Route("/api/tokens")
     * @Method("POST")
     */
    public function newTokenAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:User')
            ->findOneByEmail($request->getUser()); // getUser toć metoda HTTP basic auth.

        if(!$user) {
            throw $this->createNotFoundException('Such an email or pass does not exist');
        }

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $request->getPassword());

        //gdy nie przechodzi testu to odpalany jest JwtTokenAutnenticator
        if (!$isValid) {
            throw new BadCredentialsException();
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getEmail(),
                'exp' => time() + 3600 //czas wygasania na 1h
            ]);//do tokena można włożyć różne informacje

        return new JsonResponse([
            'token' => $token
        ]);
    }





}