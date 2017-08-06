<?php
namespace Admin\Form;

use Zend\Form\Form;
//use Zend\InputFilter\InputFilter;

class CategoryAddForm extends Form {
    public function __construct($name = null) {
        parent::__construct('categoryAddForm');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'bs-example form-horizontal');
        
        $this->add(array(
            'name' => 'categoryKey',
            'type' => 'Text',
            'options' => array(
                'min' => 3,
                'max' => 100,
                'label' => 'Key',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
        ));
        
        $this->add(array(
            'name' => 'categoryName',
            'type' => 'Text',
            'options' => array(
                'min' => 3,
                'max' => 100,
                'label' => 'Name',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'btn_submit',
                'class' => 'btn btn-primary',
                ),
        ));
    }
}