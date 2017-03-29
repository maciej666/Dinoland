<?php


namespace AddUserDinoBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer; //odc. 20 course 1 Jeżli brak @Serializer\Expose() to pole nie jest wysyłane w JsonResponse
use AddUserDinoBundle\Annotation\Link; //patrz odc. 8 course 3 o towrzeniu własnych adnotacji
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Encja rozszerza Encję FosUserBundle
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AddUserDinoBundle\Repository\UserRepository")
 * @Serializer\ExclusionPolicy("all")
 * @UniqueEntity(
 *  fields ={"name"},
 *  message="Taki Dino jest już wykluty. Nadaj swemu inne imię:)"
 * )
 *  @ORM\AttributeOverrides({
 *  @ORM\AttributeOverride(
 *      name="salt",
 *      column=@ORM\Column(name="salt", type="string", nullable=true)
 *      )
 *  })
 * @Link(
 *  "self",
 *  route = "api_show_dino",
 *  params = { "email" : "object.getEmail()" }
 * )
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=255, unique=true, nullable=false)
     * @Assert\NotBlank(message="Nie istnieją Diny bez imienia! Podaj jakieś:)")
     * @Serializer\Expose()
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "Dino-imię musi mieć co najmniej {{ limit }} znaków. ",
     *      maxMessage = "Ej! Kto widział żeby Dino-imię miało więcej niż {{ limit }} znaków.",
     *      groups={"Registration", "Profile"}
     * )
     * @Assert\Regex(
     *     pattern="/^d|^D/",
     *     match=true,
     *     message="Dino-imię musi zaczynać się od litery D(d)."
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Species", type="string", length=255)
     * @Assert\NotBlank(message="Każdy Dino należy do jakiegoś gatunku. Zmień to!")
     * @Serializer\Expose()
     * @Assert\Length(
     *      max = 200,
     *      groups={"Registration", "Profile"}
     * )
     */
    private $species;

    /**
     * @var int
     *
     * @ORM\Column(name="Age", type="integer")
     * @Serializer\Expose()
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 200,
     *      groups={"Registration", "Profile"}
     * )
     */
    private $age;

    /**
     * @var DinoParameters
     *
     * @ORM\OneToOne(targetEntity="AddUserDinoBundle\Entity\DinoParameters", inversedBy="user")
     * @ORM\JoinColumn(name="dino_id", referencedColumnName="id")
     * @Serializer\Expose()
     * odc.10 course 3
     * @Serializer\Groups({"deep"})
     */
    private $dino;


    /**
     * @var DinoMaterials
     *
     * @ORM\OneToOne(targetEntity="AddUserDinoBundle\Entity\DinoMaterials", inversedBy="user")
     * @Serializer\Expose()
     * @ORM\JoinColumn(name="materia_id", referencedColumnName="id")
     */
    private $materia;


    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AddUserDinoBundle\Entity\Blog\Post", mappedBy="user", cascade={"remove"})
     */
    private $posts;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }


    /**
     * @param string $email
     * @return $this
     *
     * Nadpisanie metody pozwala na używaniu jedynie emaila przy rejestracji
     * Źródło: http://stackoverflow.com/questions/8832916/remove-replace-the-username-field-with-email-using-fosuserbundle-in-symfony2/21064627#21064627
     */
    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set species
     *
     * @param string $species
     *
     * @return User
     */
    public function setSpecies($species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species
     *
     * @return string
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set dino
     *
     * @param \AddUserDinoBundle\Entity\DinoParameters $dino
     *
     * @return User
     */
    public function setDino(\AddUserDinoBundle\Entity\DinoParameters $dino = null)
    {
        $this->dino = $dino;

        return $this;
    }

    /**
     * Get dino
     *
     * @return \AddUserDinoBundle\Entity\DinoParameters
     */
    public function getDino()
    {
        return $this->dino;
    }


    /**
     * Set materia
     *
     * @param \AddUserDinoBundle\Entity\DinoMaterials $materia
     *
     * @return User
     */
    public function setMateria(\AddUserDinoBundle\Entity\DinoMaterials $materia = null)
    {
        $this->materia = $materia;

        return $this;
    }

    /**
     * Get materia
     *
     * @return \AddUserDinoBundle\Entity\DinoMaterials
     */
    public function getMateria()
    {
        return $this->materia;
    }

    /**
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param ArrayCollection $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }


}
