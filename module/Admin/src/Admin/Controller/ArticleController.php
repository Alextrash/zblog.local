<?php
namespace Admin\Controller;
use Application\Controller\BaseAdminController as BaseController;

class ArticleController extends BaseController
{
    public function indexAction(){
        $query = $this->getEntityManager()->createQueryBuilder();
        $query
                ->select('a')
                ->from('Blog\Entity\Article', 'a')
                ->orderBy('a.id', 'DESC');
        
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
    }
}
