<?php
namespace Label\Form;

use Zend\Form\Fieldset;
use Label\Model\Label;
use Zend\Hydrator\Reflection as ReflectionHydrator;

/**
 * Label fieldset
 * @author bojan
 */
class LabelFieldset extends Fieldset
{
    public function init() 
    {
        $this->setHydrator(new ReflectionHydrator());
        $this->setObject(new Label());
        
        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Label Name'
            ]
        ]);
        
        $this->add([
            'type' => 'textarea',
            'name' => 'default_text',
            'options' => [
                'label' => 'Default Text'
            ],
        ]);
    }
}

