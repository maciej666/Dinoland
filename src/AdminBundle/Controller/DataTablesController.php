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
    private function datatable() {
        return $this->get('datatable')
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


    /**
     * Grid action
     * @Route("/", name="datatable")
     * @return Response
     */
    public function gridAction()
    {
        return $this->datatable()->execute();                                      // call the "execute" method in your grid action
    }

    /**
     * Lists all entities.
     * @Route("/list", name="datatable_list")
     * @return Response
     */
    public function indexAction()
    {
        $this->datatable();                                                         // call the datatable config initializer
        return $this->render('AdminBundle:DataTables:materials.html.twig');                 // replace "XXXMyBundle:Module:index.html.twig" by yours
    }


}
