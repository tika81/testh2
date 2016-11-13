<?php
namespace Label\Model;

use Core\Model\Entity;

/**
 * Label entity
 * @author bojan
 */
class Label extends Entity
{
    protected $public_properties = [
        'id',
        'name',
        'default_text'
    ];
    
    /**
     * @var int
     */
    public $id;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $default_text;
}

