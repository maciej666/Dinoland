<?php

namespace AddUserDinoBundle\Controller\Api;

use AddUserDinoBundle\Entity\Blog\Post;
use AddUserDinoBundle\Form\Blog\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Post controller.
 * Security umożliwia uwierzytelnienie wszystkich zasobów klasy
 * @Security("is_granted('ROLE_USER')")
 * @Route("api")
 */
class PostApiController extends BaseController
{
    /**
     * Lists all post entities.
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     * @Route("/posts", name="api_blog_post_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $filter = $request->query->get('filter');

        $posts = $qb = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:Blog\Post')
            ->findAllPostsQueryBuilder($filter); //findAllQueryBuilder

        if(!$posts) {
            throw $this->createNotFoundException('Nie znaleziono żadnych postów');
        }

        $pagerFanta = $this->get('pagination_factory')
            ->createCollection($qb, $request, 'api_blog_post_index');

        $response = $this->createApiResponse($pagerFanta, 200);

        return $response;
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/post/new", name="api_blog_post_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        //wystarczy getUser gdyż user jest zlogowany na czas wykonywania controller'a
        $user = $this->getUser();

        //Na wszelki wypadek sprawdzenie czy podany user istnieje
        if(!$user) {
            throw $this->createNotFoundException('Such an email or pass does not exist');
        }

        $post = new Post();
        $post->setUser($user);

        $form = $this->createForm(PostType::class, $post, array(
            'csrf_protection' => false
        ));
        $this->processForm($request, $form);

        //sprawdzenie poprawności przesłanych danych
        if(!$form->isValid()){
            $this->throwApiProblemValidationException($form);
        }

        $post->setCreatedAt(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        //przekierowanie z url do strony na której możemy zobaczyć nowo stworzonego posta
        $location = $this->generateUrl('blog_post_show',[
            'slug' => $post->getSlug()
        ]);

        $response = $this->createApiResponse($post, 201);
        $response->headers->set('Location', $location); //przekierowanie

        return $response;
    }


    /**
     * Finds and displays a post entity.
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     * @Route("/post/{slug}", name="api_blog_post_show")
     * @Method("GET")
     */
    public function showAction($slug)
    {
        $post = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:Blog\Post')
            ->findOneBySlug($slug);

        if(!$post) {
            throw $this->createNotFoundException('Nie ma takiego posta '.$slug);
        }

        $response = $this->createApiResponse($post, 200);

        return $response;
    }


    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/post/edit/{slug}", name="api_blog_post_edit")
     * @Method("PATCH")
     */
    public function editAction(Request $request, $slug)
    {
        /** @var  Post $post */
        $post = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:Blog\Post')
            ->findOneBySlug($slug);

        if(!$post) {
            throw $this->createNotFoundException('Nie ma takiego posta '.$slug);
        }

        $form = $this->createForm(PostType::class, $post, array(
            'csrf_protection' => false
        ));
        $this->processForm($request, $form);

        //sprawdzenie poprawności przesłanych danych
        if(!$form->isValid()){
            $this->throwApiProblemValidationException($form);
        }

        $post->setUpdatedAt(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        $response = $this->createApiResponse($post, 201);

        return $response;
    }


    /**
     * Deletes a post entity.
     *
     * @Route("/post/delete/{slug}", name="api_blog_post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $slug)
    {
        /** @var  Post $post */
        $post = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:Blog\Post')
            ->findOneBySlug($slug);

        if(!$post) {
            throw $this->createNotFoundException('Nie ma takiego posta '.$slug);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $response = $this->createApiResponse($post, 204);

        return $response;
    }

}
