<?php

namespace AddUserDinoBundle\Controller\Blog;

use AddUserDinoBundle\Entity\Blog\Comment;
use AddUserDinoBundle\Entity\Blog\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Swift_Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
//        $cacheDriver = new \Doctrine\Common\Cache\ArrayCache();
//        $cacheDriver->deleteAll();
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
        $repo->verify();
        $repo->recover();
        $em->clear();
        $query = $em
            ->createQueryBuilder()
            ->select('node', 'u', 'i')
            ->from('AddUserDinoBundle:Blog\Comment', 'node')
            ->leftJoin('node.user', 'u')
            ->leftJoin('u.image', 'i')
            ->orderBy('node.createdAt', 'DESC')
            ->getQuery()
//            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        ;
//        $query->useResultCache(false);

        $tree = $repo->buildTree($query->getArrayResult());
        $htmlTree = $repo->childrenHierarchy();
//        dump($tree);die;

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
     * Wysyła maila z prenumeratą
     * @return JsonResponse
     */
    public function sendPdfAction(Request $request)
    {
        $email = $request->get('email');
//        dump($email);die;
        $message = \Swift_Message::newInstance()
            ->setSubject('Mail z pdf')
            ->setFrom('boban.kamienczuk666@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/pdf_email.html.twig
                    'Emails/pdf_email.html.twig'
                ),
                'text/html'
            )
            ->attach(Swift_Attachment::fromPath('bundles/adduserdino/file/MaciejJędralCvPl.pdf'))
        ;
        $this->get('mailer')->send($message);

        $response = new JsonResponse();
        $response->setData(array(
            "code" => 200,
            "success" => true,
        ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * Allowed to download file
     *
     * @Route("/download/pdf", name="blog_download_pdf")
     *
     * @Method("GET")
     */
    public function downloadPdfAction()
    {
        // You only need to provide the path to your static file
        // $filepath = 'path/to/TextFile.txt';

        // i.e Sending a file from the resources folder in /web
        // in this example, the TextFile.txt needs to exist in the server
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../web/bundles/adduserdino/file/';
        $filename = "MaciejJędralCvPl.pdf";

        // check if file exists
        $fs = new FileSystem();
        if (!$fs->exists($publicResourcesFolderPath.$filename)) {
            throw $this->createNotFoundException();
        }

        // This should return the file to the browser as response
        $response = new BinaryFileResponse($publicResourcesFolderPath.$filename);

        // To generate a file download, you need the mimetype of the file
        $mimeTypeGuesser = new FileinfoMimeTypeGuesser();

        // Set the mimetype with the guesser or manually
        if($mimeTypeGuesser->isSupported()){
            // Guess the mimetype of the file according to the extension of the file
            $response->headers->set('Content-Type', $mimeTypeGuesser->guess($publicResourcesFolderPath.$filename));
        }else{
            // Set the mimetype of the file manually, in this case for a text file is text/plain
            $response->headers->set('Content-Type', 'text/plain');
        }

        // Set content disposition inline of the file
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );

        return $response;
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
