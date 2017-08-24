<?php

namespace Admin\Controller;

use Application\Controller\BaseAdminController as BaseController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Blog\Entity\Article;
use Admin\Form\ArticleAddForm;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

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

        return array('articles' => $paginator);
    }
    
    public function addAction(){
        $em = $this->getEntityManager();
        $form = new ArticleAddForm($em);
        
        $request = $this->getRequest();
        if($request->isPost()){
            $message = $status = '';
            
            $data = $request->getPost();
            
            $article = new Article();
            $form->setHydrator(new DoctrineHydrator($em, '\Article'));
            $form->bind($article);
            $form->setData($data);
            
            if($form->isValid()){
                $em->persist($article);
                $em->flush();
                
                $status = 'success';
                $message = 'Article was added';
            } else {
                $status = 'error';
                $message = 'Article wasn\'t added';
                
                foreach ($form->getInputFilter()->getInvalidInput() as $errors){
                    foreach ($errors->getMessages() as $error){
                        $message .= ' ' . $error;
                    }
                }
            }
        } else {
            return array('form' => $form);
        }
        
        if($message){
            $this->flashMessenger()
                    ->setNamespace($status)
                    ->addMessage($message);
        }
        return $this->redirect()->toRoute('admin/article');
    }
    
    public function editAction(){
        $em = $this->getEntityManager();
        $status = $message = '';
        $form = new ArticleAddForm($em);
        
        $id = (int) $this->params()->fromRoute('id', 0);
        $article = $em->find('Blog\Entity\Article', $id);

        if(empty($article)){
            $message = "Article not found";
            $status = 'error';
            $this->flashMessenger()
                    ->setNamespace($status)
                        ->addMessage($message);
            return $this->redirect()->toRoute('admin/article');
        }
        
        $form->setHydrator(new DoctrineHydrator($em, '\Article'));
        $form->bind($article);
        
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            
            if($form->isValid()){
                $em->persist($article);
                $em->flush();
                
                $status = 'success';
                $message = 'Article was updated';
            } else {
                $status = 'error';
                $message = 'Article wasn\'t updated. Parameter\'s error!';
                foreach ($form->getInputFilter()->getInvalidInput() as $errors){
                    foreach($errors->getMessages() as $error){
                        $message .= ' ' . $error;
                    }
                }
            } 
        } else {
            return array('form' => $form, 'id' => $id);
        }
        
        if($message){
            $this->flashMessenger()
                    ->setNamespace($status)
                        ->addMessage($message);
        }
        
        return $this->redirect()->toRoute('admin/article');
    }
    
    public function deleteAction(){
        
        $em = $this->getEntityManager();
        $id = (int) $this->params()->fromRoute('id', 0);
        $status = 'success';
        $message = 'Record was deleted';
        
        try {
            $repository = $em->getRepository('Blog\Entity\Article');
            $article = $repository->find($id);
            $em->remove($article);
            $em->flush();
        } catch (\Exception $ex) {
            $status = 'error';
            $message = 'Category wasn\'t deleted' . $ex->getMessage();
        }
        
        $this->flashMessenger()
                ->setNamespace($status)
                    ->addMessage($message);
        return $this->redirect()->toRoute('admin/article');
    }
}