<?php

namespace Admin\Form;

use Zend\Form\Form;
//use Zend\Form\Element;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Admin\Filter\ArticleAddInputFilter;
//use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
//use Blog\Entity\Article;

class ArticleAddForm extends Form implements ObjectManagerAwareInterface {

    protected $objectManager;

    public function setObjectManager(ObjectManager $objectManager) {
        $this->objectManager = $objectManager;
    }

    public function getObjectManager() {
        return $this->objectManager;
    }

    public function __construct(ObjectManager $objectManager) {
        parent::__construct('articleAddForm');
        $this->setObjectManager($objectManager);
        $this->createElements();
    }

    public function createElements() {
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'bs-example form-horizontal');

        $this->setInputFilter(new ArticleAddInputFilter());

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'category',
            'options' => array(
                'label' => 'Category',
                'empty_option' => 'Select Category',
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'Blog\Entity\Category',
                'property' => 'categoryName',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            )
        ));

        $this->add(array(
            'type' => 'Text',
            'name' => 'title',
            'options' => array(
                'min' => 3,
                'max' => 100,
                'label' => 'Title'
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            )
        ));

        $this->add(array(
            'type' => 'Textarea',
            'name' => 'shortArticle',
            'options' => array(
                'label' => 'Shot article'
            ),
            'attributes' => array(
                'class' => 'form-control ckeditor',
            ),
        ));

        $this->add(array(
            'type' => 'Textarea',
            'name' => 'article',
            'options' => array(
                'label' => 'Article'
            ),
            'attributes' => array(
                'class' => 'form-control ckeditor',
            ),
        ));

        $this->add(array(
            'type' => 'Checkbox',
            'name' => 'isPublic',
            'options' => array(
                'label' => 'Publicate',
                'use_hidden_Element' => true,
                'checked_value' => 1,
                'unchecked_value' => 0,
            ),
        ));

        $this->add(array(
            'type' => 'Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'btn_submit',
                'class' => 'btn btn-primary'
            ),
        ));
    }

}
