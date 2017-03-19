<?php

namespace AddUserDinoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Encja zawiera treść Dino-schronów.
 *
 * DinoContent
 *
 * @ORM\Table(name="dino_content")
 * @ORM\Entity(repositoryClass="AddUserDinoBundle\Repository\DinoContentRepository")
 */
class DinoContent
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
     *
     * @ORM\Column(name="foto", type="string", length=255)
     */
    private $foto;

    /**
     * @var string
     *
     * @ORM\Column(name="home_name", type="string", length=255)
     */
    private $homeName;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionLvl1", type="string", length=255)
     */
    private $descriptionLvl1;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionLvl2", type="string", length=255)
     */
    private $descriptionLvl2;

    /**
     * @var int
     *
     * @ORM\Column(name="req_wood_1", type="integer")
     */
    private $reqWood1;

    /**
     * @var int
     *
     * @ORM\Column(name="req_wood_2", type="integer")
     */
    private $reqWood2;

    /**
     * @var int
     *
     * @ORM\Column(name="req_stone_1", type="integer")
     */
    private $reqStone1;

    /**
     * @var int
     *
     * @ORM\Column(name="req_stone_2", type="integer")
     */
    private $reqStone2;

    /**
     * @var int
     *
     * @ORM\Column(name="req_bone_1", type="integer")
     */
    private $reqBone1;

    /**
     * @var int
     *
     * @ORM\Column(name="req_bone_2", type="integer")
     */
    private $reqBone2;

    /**
     * @var int
     *
     * @ORM\Column(name="req_flint_1", type="integer")
     */
    private $reqFlint1;

    /**
     * @var int
     *
     * @ORM\Column(name="req_flint_2", type="integer")
     */
    private $reqFlint2;


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
     * Set foto
     *
     * @param string $foto
     *
     * @return DinoContent
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set homeName
     *
     * @param string $homeName
     *
     * @return DinoContent
     */
    public function setHomeName($homeName)
    {
        $this->homeName = $homeName;

        return $this;
    }

    /**
     * Get homeName
     *
     * @return string
     */
    public function getHomeName()
    {
        return $this->homeName;
    }

    /**
     * Set descriptionLvl1
     *
     * @param string $descriptionLvl1
     *
     * @return DinoContent
     */
    public function setDescriptionLvl1($descriptionLvl1)
    {
        $this->descriptionLvl1 = $descriptionLvl1;

        return $this;
    }

    /**
     * Get descriptionLvl1
     *
     * @return string
     */
    public function getDescriptionLvl1()
    {
        return $this->descriptionLvl1;
    }

    /**
     * Set descriptionLvl2
     *
     * @param string $descriptionLvl2
     *
     * @return DinoContent
     */
    public function setDescriptionLvl2($descriptionLvl2)
    {
        $this->descriptionLvl2 = $descriptionLvl2;

        return $this;
    }

    /**
     * Get descriptionLvl2
     *
     * @return string
     */
    public function getDescriptionLvl2()
    {
        return $this->descriptionLvl2;
    }

    /**
     * Set reqWood1
     *
     * @param integer $reqWood1
     *
     * @return DinoContent
     */
    public function setReqWood1($reqWood1)
    {
        $this->reqWood1 = $reqWood1;

        return $this;
    }

    /**
     * Get reqWood1
     *
     * @return int
     */
    public function getReqWood1()
    {
        return $this->reqWood1;
    }

    /**
     * Set reqWood2
     *
     * @param integer $reqWood2
     *
     * @return DinoContent
     */
    public function setReqWood2($reqWood2)
    {
        $this->reqWood2 = $reqWood2;

        return $this;
    }

    /**
     * Get reqWood2
     *
     * @return int
     */
    public function getReqWood2()
    {
        return $this->reqWood2;
    }

    /**
     * Set reqStone1
     *
     * @param integer $reqStone1
     *
     * @return DinoContent
     */
    public function setReqStone1($reqStone1)
    {
        $this->reqStone1 = $reqStone1;

        return $this;
    }

    /**
     * Get reqStone1
     *
     * @return int
     */
    public function getReqStone1()
    {
        return $this->reqStone1;
    }

    /**
     * Set reqStone2
     *
     * @param integer $reqStone2
     *
     * @return DinoContent
     */
    public function setReqStone2($reqStone2)
    {
        $this->reqStone2 = $reqStone2;

        return $this;
    }

    /**
     * Get reqStone2
     *
     * @return int
     */
    public function getReqStone2()
    {
        return $this->reqStone2;
    }

    /**
     * Set reqBone1
     *
     * @param integer $reqBone1
     *
     * @return DinoContent
     */
    public function setReqBone1($reqBone1)
    {
        $this->reqBone1 = $reqBone1;

        return $this;
    }

    /**
     * Get reqBone1
     *
     * @return int
     */
    public function getReqBone1()
    {
        return $this->reqBone1;
    }

    /**
     * Set reqBone2
     *
     * @param integer $reqBone2
     *
     * @return DinoContent
     */
    public function setReqBone2($reqBone2)
    {
        $this->reqBone2 = $reqBone2;

        return $this;
    }

    /**
     * Get reqBone2
     *
     * @return int
     */
    public function getReqBone2()
    {
        return $this->reqBone2;
    }

    /**
     * Set reqFlint1
     *
     * @param integer $reqFlint1
     *
     * @return DinoContent
     */
    public function setReqFlint1($reqFlint1)
    {
        $this->reqFlint1 = $reqFlint1;

        return $this;
    }

    /**
     * Get reqFlint1
     *
     * @return int
     */
    public function getReqFlint1()
    {
        return $this->reqFlint1;
    }

    /**
     * Set reqFlint2
     *
     * @param integer $reqFlint2
     *
     * @return DinoContent
     */
    public function setReqFlint2($reqFlint2)
    {
        $this->reqFlint2 = $reqFlint2;

        return $this;
    }

    /**
     * Get reqFlint2
     *
     * @return int
     */
    public function getReqFlint2()
    {
        return $this->reqFlint2;
    }
}

