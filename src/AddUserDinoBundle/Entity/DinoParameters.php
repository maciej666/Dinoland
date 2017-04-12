<?php

namespace AddUserDinoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * Encja ze statystykami użytkownika
 * DinoParameters
 *
 * @ORM\Table(name="dino_parameters")
 * @ORM\Entity(repositoryClass="AddUserDinoBundle\Repository\DinoParametersRepository")
 */
class DinoParameters extends Timestampable
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
     * @var int
     * @Assert\NotBlank(message="Dino bez zdrowia nie żyje!")
     * @ORM\Column(name="health", type="integer")
     */
    private $health;

    /**
     * @var int
     *
     * @ORM\Column(name="backup", type="integer")
     */
    private $backup;

    /**
     * @var int
     *
     * @ORM\Column(name="speed", type="integer")
     */
    private $speed;

    /**
     * @var int
     *
     * @ORM\Column(name="strength", type="integer")
     */
    private $strength;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="AddUserDinoBundle\Entity\User", mappedBy="dino")
     */
    private $user;


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
     * Set health
     *
     * @param integer $health
     *
     * @return DinoParameters
     */
    public function setHealth($health)
    {
        $this->health = $health;

        return $this;
    }

    /**
     * Get health
     *
     * @return int
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * Set backup
     *
     * @param integer $backup
     *
     * @return DinoParameters
     */
    public function setBackup($backup)
    {
        $this->backup = $backup;

        return $this;
    }

    /**
     * Get backup
     *
     * @return int
     */
    public function getBackup()
    {
        return $this->backup;
    }

    /**
     * Set speed
     *
     * @param integer $speed
     *
     * @return DinoParameters
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * Get speed
     *
     * @return int
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Set strength
     *
     * @param integer $strength
     *
     * @return DinoParameters
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * Get strength
     *
     * @return int
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * Set user
     *
     * @param \AddUserDinoBundle\Entity\User $user
     *
     * @return DinoParameters
     */
    public function setUser(\AddUserDinoBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AddUserDinoBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
