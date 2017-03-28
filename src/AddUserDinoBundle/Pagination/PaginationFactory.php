<?php

namespace AddUserDinoBundle\Pagination;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class PaginationFactory
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function createCollection(QueryBuilder $qb, Request $request, $route, $routeParams = array())
    {
        //wartość domyślna ustawiona na pierwszą stronę
        $page = $request->query->get('page', 1);

        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($page);

        //aby mieć array'a z obiektami user
        $users = array();
        foreach ($pagerfanta->getCurrentPageResults() as $user) {
            $users[] = $user;
        }

        $paginatedCollection = new PaginatedCollection(
            $users, //wyświetlane obiekty
            $pagerfanta->getNbResults() //całkowita ilośc tych obiektów
        );

        //aby utrzymać filtr poprzez wszystkie paginacje, odc. 5 course 3
        $routeParams = array_merge($routeParams, $request->query->all());
        //use pozwala używać parametrów w środku definiowanej funkcji
        $createLinkUrl = function($targetPage) use ($route, $routeParams) {
            return $this->router->generate($route, array_merge(
                $routeParams,
                array('page' => $targetPage)
            ));
        };

        //nazwy rel'i są oficjalnym standardem
        $paginatedCollection->addLink('self', $createLinkUrl($page)); //link do strony na której jesteśmy :)
        $paginatedCollection->addLink('first', $createLinkUrl(1));
        $paginatedCollection->addLink('last', $createLinkUrl($pagerfanta->getNbPages()));

        if ($pagerfanta->hasNextPage()) {
            $paginatedCollection->addLink('next', $createLinkUrl($pagerfanta->getNextPage()));
        }
        if ($pagerfanta->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $createLinkUrl($pagerfanta->getPreviousPage()));
        }

        return $paginatedCollection;
    }
}