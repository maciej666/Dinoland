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
     * Finds and displays a post entity with comments.
     *
     * @Route("/post/{slug}", name="blog_post_show")
     * @Template
     * @Method("GET")
     */
    public function showAction(Request $request, Post $post)
    {
        $form = $this->createForm('AddUserDinoBundle\Form\Blog\CommentType');
        $form2 = $this->createForm('AddUserDinoBundle\Form\Blog\CommentType');

//        $em = $this->getDoctrine()->getManager();
//
//        $user = $this->getUser();
//        $post = $em->getRepository('AddUserDinoBundle:Blog\Post')->findOneById(3);
//
//        $c1 = new Comment();
//        $c1->setAuthorName('parent');
//        $c1->setBody('parent body');
//        $c1->setUser($user);
//        $c1->setPost($post);
//
//        $c2 = new Comment();
//        $c2->setAuthorName('child');
//        $c2->setBody('child body');
//        $c2->setUser($user);
//        $c2->setPost($post);
//        $c2->setParent($c1);
//
//        $c3 = new Comment();
//        $c3->setAuthorName('child second');
//        $c3->setBody('child body second');
//        $c3->setUser($user);
//        $c3->setPost($post);
//        $c3->setParent($c1);
//
//        $em->persist($post);
//        $em->persist($user);
//        $em->persist($c1);
//        $em->persist($c2);
//        $em->persist($c3);
//        $em->flush();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AddUserDinoBundle:Blog\Comment');

        $query = $em
            ->createQueryBuilder()
            ->select('node', 'u', 'i')
            ->from('AddUserDinoBundle:Blog\Comment', 'node')
            ->leftJoin('node.user', 'u')
            ->leftJoin('u.image', 'i')
            ->orderBy('node.createdAt', 'DESC')
            ->getQuery()
        ;

        $tree = $repo->buildTree($query->getArrayResult());
        $htmlTree = $repo->childrenHierarchy();
        dump($htmlTree);die;

        return array(
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'post' => $post,
            'htmltree' => $tree,
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
