<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PanelController
 * @package AdminBundle\Controller
 * @Route("/datatables")
 */
class DataTablesController extends Controller
{

//----------------------------------   https://github.com/waldo2188/DatatableBundle#how-to-use-datatablebundle-    ------------------------------------


    /**
     * set datatable configs
     * @return \Waldo\DatatableBundle\Util\Datatable
     */
    private function materialsDatatable() {
        return $this->get('datatable')
            ->setDatatableId('materials')
            ->setEntity("AddUserDinoBundle:DinoMaterials", "m")                          // replace "XXXMyBundle:Entity" by your entity
            ->setFields(
                array(
                    "Wood"          => 'm.wood',                        // Declaration for fields:
                    "Stone"       => 'm.stone',                     // "label" => "alias.field_attribute_for_dql"
                    "Bone"       => 'm.bone',                     // "label" => "alias.field_attribute_for_dql"
                    "Flint"       => 'm.flint',                     // "label" => "alias.field_attribute_for_dql"
                    "Access"       => 'm.access',                     // "label" => "alias.field_attribute_for_dql"
//                    "Total"         => 'COUNT(m.wood) as total',      // Use SQL commands, you must always define an alias
                    "_identifier_"  => 'm.id')                          // you have to put the identifier field without label. Do not replace the "_identifier_"
            )
            ->setOrder("m.id", "desc")
            ->setGlobalSearch(true)
            ;
    }


    protected function parametersDatatable()
    {
        // ...
        return $this->get('datatable')
            ->setDatatableId('parameters')
            ->setEntity("AddUserDinoBundle:DinoParameters", "p")
            ->setFields(
                array(
                    "Health"          => 'p.health',                        // Declaration for fields:
                    "Speed"       => 'p.speed',                     // "label" => "alias.field_attribute_for_dql"
                    "Strength"       => 'p.strength',                     // "label" => "alias.field_attribute_for_dql"
                    "Backup"       => 'p.backup',                     // "label" => "alias.field_attribute_for_dql"
                    "_identifier_"  => 'p.id')                          // you have to put the identifier field without label. Do not replace the "_identifier_"
            )
            ->setOrder("p.id", "desc")
            ->setGlobalSearch(true)
            ;
    }


    protected function usersDatatable()
    {
        // ...
        return $this->get('datatable')
            ->setDatatableId('users')
            ->setEntity("AddUserDinoBundle:User", "u")
            ->setFields(
                array(
                    "Dino-imię"          => 'u.name',                        // Declaration for fields:
                    "Gatunek"       => 'u.species',                     // "label" => "alias.field_attribute_for_dql"
                    "Wiek"       => 'u.age',                     // "label" => "alias.field_attribute_for_dql"
                    "Email"       => 'u.email',                     // "label" => "alias.field_attribute_for_dql"
//                    "Image"       => 'u.image',                     // "label" => "alias.field_attribute_for_dql"
                    "_identifier_"  => 'u.id')                          // you have to put the identifier field without label. Do not replace the "_identifier_"
            )
            ->setOrder("u.id", "desc")
            ->setGlobalSearch(true)
            ;
    }


    /**
     * Grid action
     * @Route("/materials_datatable", name="materials_datatable")
     * @return Response
     */
    public function gridAction()
    {
        return $this->materialsDatatable()->execute();                                      // call the "execute" method in your grid action
    }


    /**
     * Grid action
     * @Route("/parameters_datatable", name="parameters_datatable")
     * @return Response
     */
    public function gridSecondAction()
    {
        return $this->parametersDatatable()->execute();                                      // call the "execute" method in your grid action
    }


    /**
     * Grid action
     * @Route("/users_datatable", name="users_datatable")
     * @return Response
     */
    public function gridThirdAction()
    {
        return $this->usersDatatable()->execute();                                      // call the "execute" method in your grid action
    }


    /**
     * Lists all entities.
     * @Route("/list", name="datatable_list")
     * @return Response
     */
    public function indexAction()
    {
        $this->materialsDatatable();                                                         // call the datatable config initializer
        $this->parametersDatatable();                                                         // call the datatable config initializer
        $this->usersDatatable();                                                         // call the datatable config initializer
        return $this->render('AdminBundle:DataTables:materials.html.twig');                 // replace "XXXMyBundle:Module:index.html.twig" by yours
    }


}
