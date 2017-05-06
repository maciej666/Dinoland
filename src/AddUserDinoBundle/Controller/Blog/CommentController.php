<?php

namespace AddUserDinoBundle\Controller\Blog;

use AddUserDinoBundle\Entity\Blog\Comment;
use AddUserDinoBundle\Entity\Blog\Post;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Post controller.
 *
 * @Route("blog")
 */
class CommentController extends Controller
{
    /**
     * Creates comment.
     *
     * @Route("/{slug}/comment/create/{parentId}", defaults={"parentId" = null}, name="blog_create_comment")
     *
     * @Method("POST")
     */
    public function createCommentAction(Request $request, $slug, $parentId)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AddUserDinoBundle:Blog\Post');
        $post = $repository->findOneBySlug($slug);
        $user = $this->getUser();

        if(!$post) {
            throw new HttpException('Nie ma takiego posta');
        }

        $form = $this->container->get('dino_blog_manager')->createComment($post, $request, $user, $parentId);
        if (true === $form) {
            $this->get('session')->getFlashBag()->add('success', 'Dodano komentarz');
            return $this->redirect($this->generateUrl('blog_post_show', array(
                'slug' => $post->getSlug()
            )));
        }
        return $this->redirect($this->generateUrl('blog_post_show', array(
            'slug' => $slug,
            'post' => $post,
            'form' => $form->createView()
        )));
    }


    /**
     * Zwraca świeże komentarze
     * @return JsonResponse
     * @throws \Exception
     */
    public function loadCommentsAction(Request $request)
    {
        $last_comment = $request->request->get('last_comment');
        $post_id = $request->request->get('post_id');
        $repository = $this->getDoctrine()->getManager()->getRepository('AddUserDinoBundle:Blog\Comment');
        $fresh_comments = $repository->getFreshCommentsTree($post_id, $last_comment);

        $check_parent = $this->container->get('dino_blog_manager')->whatever($fresh_comments, 'lft', '2');
//        dump('hahaha');die;
//        dump($fresh_comments, $check_parent);die;

        $template = $this->render('AddUserDinoBundle:Blog/Post:commentAjax.html.twig',array(
            'fresh_comments' => $fresh_comments,
        ))->getContent();
        $content = json_encode($template);
        $response = new JsonResponse();

        if ($fresh_comments != null) {
            $response->setData(array(
                "code" => 200, //200 z jakiegoś powodu nie działa??
                "success" => true,
                "content" => $content,
//                "child_comments" => $child_comments
            ));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            $response->setData(array(
                "code" => 204, //200 z jakiegoś powodu nie działa??
                "success" => true,
            ));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

}
