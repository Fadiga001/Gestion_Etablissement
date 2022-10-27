<?php

namespace App\Services;

use App\Repository\EtudiantRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class EtudiantServices
{
    public function __construct(private RequestStack $requestStack, private EtudiantRepository $etudiantRepo, private PaginatorInterface $paginator)
    {
        
    }

    public function getPaginateEtudiant()
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 6;
        $EtudiantQuery = $this->etudiantRepo->findForPaginationEtudiant();

        return $this->paginator->paginate($EtudiantQuery, $page, $limit);
    }
}