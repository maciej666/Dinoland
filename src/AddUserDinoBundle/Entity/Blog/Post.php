<?php

namespace AddUserDinoBundle\Entity\Blog;

use AddUserDinoBundle\Entity\Timestampable;
use AddUserDinoBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use AddUserDinoBundle\Annotation\Link; //patrz odc. 8 course 3 o towrzeniu własnych adnotacji, dodatkowe pole dodaje LinkSerializationSubscriber
use JMS\Serializer\Annotation as Serializer; //odc. 20 course 1 Jeżli brak @Serializer\Expose() to pole nie jest wysyłane w JsonResponse


/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AddUserDinoBundle\Repository\PostRepository")
 * @Serializer\ExclusionPolicy("all")
 * @Link(
 *  "self",
 *  route = "api_blog_post_show",
 *  params = { "slug" : "object.getSlug()" }
 * )
 */
class Post extends Timestampable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Proszę podać tytuł")
     * @Serializer\Expose()
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message="Proszę coś napisać")
     * @Serializer\Expose()
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @Serializer\Expose()
     * @ORM\Column(length=255)
     */
    private $slug;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AddUserDinoBundle\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank(message="Nie ma posta bez autora")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AddUserDinoBundle\Entity\Blog\Comment", mappedBy="post")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $comments;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Post
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }
}

