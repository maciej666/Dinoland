<?php

namespace AddUserDinoBundle\Controller\Blog;

use AddUserDinoBundle\Entity\Blog\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AddUserDinoBundle:Blog\Post')->findAll();

        return $this->render('blog/post/index.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/new/post", name="blog_post_new")
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
            return $this->redirectToRoute('blog_post_show', array('id' => $post->getId()));
        }

        return $this->render('blog/post/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("/post/{id}", name="blog_post_show")
     * @Method("GET")
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);

        return $this->render('blog/post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/post/{id}/edit", name="blog_post_edit")
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
            return $this->redirectToRoute('blog_post_edit', array('id' => $post->getId()));
        }

        return $this->render('blog/post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a post entity.
     *
     * @Route("/post/{id}", name="blog_post_delete")
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
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('blog_post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
