<?php

namespace AddUserDinoBundle\Controller\Blog;

use AddUserDinoBundle\Entity\Blog\Comment;
use AddUserDinoBundle\Entity\Blog\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Post controller.
 *
 * @Route("blog")
 */
class PostController extends Controller
{
    /**
     * Lists all post entities.
     *
     * @Route("/posts", name="blog_post_index")
     * @Template
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $filter = $request->query->get('filter');

        $qb = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:Blog\Post')
            ->findAllPostsQueryBuilder($filter);

        $pagerFanta = $this->get('pagination_factory')
            ->createPagerFanta($qb, $request, 'blog_post_index', array(), 5)[0];

        return array(
            'paginatedCollection' => $pagerFanta
        );
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/new/post", name="blog_post_new")
     * @Template
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $Session = $this->get('session');
        $post = new Post();
        $post->setUser($this->getUser());
        $form = $this->createForm('AddUserDinoBundle\Form\Blog\PostType', $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $Session->getFlashBag()->add('success', 'Stworzono post :)');
            return $this->redirectToRoute('blog_post_show', array('slug' => $post->getSlug()));
        }

        return array(
            'post' => $post,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("/post/{slug}", name="blog_post_show")
     * @Template
     * @Method("GET")
     */
    public function showAction(Request $request, Post $post)
    {
        $form = $this->createForm('AddUserDinoBundle\Form\Blog\CommentType');

        return array(
            'form' => $form->createView(),
            'post' => $post,
        );
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/post/{slug}/edit", name="blog_post_edit")
     * @Template
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post)
    {
        $Session = $this->get('session');
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('AddUserDinoBundle\Form\Blog\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $Session->getFlashBag()->add('success', 'Edytowano post :)');
            return $this->redirectToRoute('blog_post_edit', array('slug' => $post->getSlug()));
        }

        return array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a post entity.
     *
     * @Route("/post/{slug}", name="blog_post_delete")
     *
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $Session = $this->get('session');
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
            $Session->getFlashBag()->add('info', 'Post usunięty :)');
        }

        return $this->redirectToRoute('blog_post_index');
    }


    /**
     * Creates comment.
     *
     * @Route("/{slug}/comment/create", name="blog_create_comment")
     *
     * @Method("POST")
     */
    public function createCommentAction(Request $request, $slug)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AddUserDinoBundle:Blog\Post');
        $post = $repository->findOneBySlug($slug);
        $user = $this->getUser();

        if(!$post) {
            throw new HttpException('Nie ma takiego posta');
        }

        $form = $this->container->get('dino_blog_manager')->createComment($post, $request, $user);
        if (true === $form){
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
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('blog_post_delete', array('slug' => $post->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
