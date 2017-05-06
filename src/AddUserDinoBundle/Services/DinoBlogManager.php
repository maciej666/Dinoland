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
    public function createComment(Post $post, Request $request, $user, $parentId)
    {
        $comment = new Comment();
        $comment->setPost($post);
        $comment->setUser($user);

        $form = $this->formFactory->create(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isValid()){
            //if comment has parent then save parent relation
            if($parentId != null) {
                $repo = $this->em->getRepository('AddUserDinoBundle:Blog\Comment');
                $parentComment = $repo->findOneById($parentId);
//                dump($parentComment);die;
                $comment->setParent($parentComment);
                $this->em->persist($parentComment);
            }
            $this->em->persist($comment);
            $this->em->persist($post);
            $this->em->persist($user);
//            $repo->verify();
//            $repo->reorder(null/*reorder starting from parent*/, 'createdAt', 'DESC', true);

//            $this->em->getRepository('AddUserDinoBundle:Blog\Comment')->persistAsLastChildOf($comment, $parentComment);
            $this->em->flush();
            $repo->verify();
            $repo->recover();
            $this->em->clear();

            return true;
        }
        return $form;
    }

    /**
     * Sprawdza czy tablica ma podaną parę wartośc klucz
     * @param $array
     * @param $key
     * @param $val
     * @return bool
     */
    public function whatever($array, $key, $val) {
        foreach ($array as $item)
            if (isset($item[$key]) && $item[$key] == $val)
                return true;
        return false;
    }



}