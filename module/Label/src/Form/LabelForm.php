<?php
namespace Label\Form;

use Zend\Form\Form;

/**
 * Label Form
 * @author bojan
 */
class LabelForm extends Form
{
    public function init() 
    {
        $this->add([
            'name' => 'label',
            'type' => LabelFieldset::class,
            'options' => [
                'use_as_base_fieldset' => true,
            ],
        ]);
        
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Insert new Label'
            ],
        ]);
    }
}

