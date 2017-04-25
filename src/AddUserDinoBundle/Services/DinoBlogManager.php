<?php

namespace AddUserDinoBundle\Services;

use AddUserDinoBundle\Entity\Blog\Comment;
use AddUserDinoBundle\Entity\Blog\Post;
use AddUserDinoBundle\Form\Blog\CommentType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DinoManager
 * @package AddUserDinoBundle\Services
 */
class DinoBlogManager
{
    private $em;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param EntityManager         $em
     */
    public function __construct(EntityManager $em, FormFactoryInterface $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    /**
     *
     * @param Post      $post
     * @param Request   $request
     *
     * @return FormInterface|boolean
     */
    public function createComment(Post $post, Request $request, $user)
    {
        $comment = new Comment();
        $comment->setPost($post);
        $comment->setUser($user);

        $form = $this->formFactory->create(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->em->persist($comment);
            $this->em->flush();

            return true;
        }
        return $form;
    }

}