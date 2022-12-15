<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class paginationServices
{
    private $entityClass;
    private  $limit = 10; 
    private $currentPage = 1;
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getPages()
    {
        // 1) connaitre le total des enregistrements de la table

        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        // 2) Diviser, arrondir et renvoyer

        $pages = ceil($total / $this->limit);
        return $pages;
    }

    public function getData()
    {
        // 1) Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2) Demander au repository de troyver les elements

        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        //Renvoyer les elements en question

        return $data;
    }


    public function setPage($page)
    {
        $this->CurrentPage = $page;

        return $this;
    }

    public function getPage()
     {
        return $this->currentPage;
     }


    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
     {
        return $this->limit;
     }

    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass(){
       return  $this->entityClass;

    }

    
}