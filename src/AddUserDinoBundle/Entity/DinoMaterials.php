<?php

namespace AddUserDinoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Encja z surowcami Dina
 *
 * DinoMaterials
 *
 * @ORM\Table(name="dino_materials")
 * @ORM\Entity(repositoryClass="AddUserDinoBundle\Repository\DinoMaterialsRepository")
 */
class DinoMaterials extends Timestampable
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
     *
     * @ORM\Column(name="stone", type="integer")
     * @Assert\NotBlank()
     *
     */
    private $stone;

    /**
     * @var int
     *
     * @ORM\Column(name="stone_extra", type="integer", nullable=true)
     */
    private $stone_extra;

    /**
     * @var int
     *
     * @ORM\Column(name="wood", type="integer")
     */
    private $wood;

    /**
     * @var int
     *
     * @ORM\Column(name="wood_extra", type="integer", nullable=true)
     */
    private $wood_extra;

    /**
     * @var int
     *
     * @ORM\Column(name="bone", type="integer")
     */
    private $bone;

    /**
     * @var int
     *
     * @ORM\Column(name="bone_extra", type="integer", nullable=true)
     */
    private $bone_extra;

    /**
     * @var int
     *
     * @ORM\Column(name="flint", type="integer")
     */
    private $flint;

    /**
     * @var int
     *
     * @ORM\Column(name="flint_extra", type="integer", nullable=true)
     */
    private $flint_extra;

    /**
     * @var int
     *
     * @ORM\Column(name="access", type="integer", nullable=true)
     */
    private $access;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="AddUserDinoBundle\Entity\User", mappedBy="materia")
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
     * Set stone
     *
     * @param string $stone
     *
     * @return DinoMaterials
     */
    public function setStone($stone)
    {
        $this->stone = $stone;

        return $this;
    }

    /**
     * Get stone
     *
     * @return string
     */
    public function getStone()
    {
        return $this->stone;
    }

    /**
     * Set wood
     *
     * @param integer $wood
     *
     * @return DinoMaterials
     */
    public function setWood($wood)
    {
        $this->wood = $wood;

        return $this;
    }

    /**
     * Get wood
     *
     * @return int
     */
    public function getWood()
    {
        return $this->wood;
    }

    /**
     * Set bone
     *
     * @param integer $bone
     *
     * @return DinoMaterials
     */
    public function setBone($bone)
    {
        $this->bone = $bone;

        return $this;
    }

    /**
     * Get bone
     *
     * @return int
     */
    public function getBone()
    {
        return $this->bone;
    }

    /**
     * Set flint
     *
     * @param integer $flint
     *
     * @return DinoMaterials
     */
    public function setFlint($flint)
    {
        $this->flint = $flint;

        return $this;
    }

    /**
     * Get flint
     *
     * @return int
     */
    public function getFlint()
    {
        return $this->flint;
    }

    /**
     * Set user
     *
     * @param \AddUserDinoBundle\Entity\User $user
     *
     * @return DinoMaterials
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

    /**
     * Set stoneExtra
     *
     * @param integer $stoneExtra
     *
     * @return DinoMaterials
     */
    public function setStoneExtra($stoneExtra)
    {
        $this->stone_extra = $stoneExtra;

        return $this;
    }

    /**
     * Get stoneExtra
     *
     * @return integer
     */
    public function getStoneExtra()
    {
        return $this->stone_extra;
    }

    /**
     * Set woodExtra
     *
     * @param integer $woodExtra
     *
     * @return DinoMaterials
     */
    public function setWoodExtra($woodExtra)
    {
        $this->wood_extra = $woodExtra;

        return $this;
    }

    /**
     * Get woodExtra
     *
     * @return integer
     */
    public function getWoodExtra()
    {
        return $this->wood_extra;
    }

    /**
     * Set boneExtra
     *
     * @param integer $boneExtra
     *
     * @return DinoMaterials
     */
    public function setBoneExtra($boneExtra)
    {
        $this->bone_extra = $boneExtra;

        return $this;
    }

    /**
     * Get boneExtra
     *
     * @return integer
     */
    public function getBoneExtra()
    {
        return $this->bone_extra;
    }

    /**
     * Set flintExtra
     *
     * @param integer $flintExtra
     *
     * @return DinoMaterials
     */
    public function setFlintExtra($flintExtra)
    {
        $this->flint_extra = $flintExtra;

        return $this;
    }

    /**
     * Get flintExtra
     *
     * @return integer
     */
    public function getFlintExtra()
    {
        return $this->flint_extra;
    }

    /**
     * Set access
     *
     * @param integer $access
     *
     * @return DinoMaterials
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return integer
     */
    public function getAccess()
    {
        return $this->access;
    }
}
