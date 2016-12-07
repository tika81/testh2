<?php
namespace Label\Model;

use Core\Model\Entity;

/**
 * Label entity
 * @author bojan
 */
class Label extends Entity
{
    /**
     * Public properties
     * @var array
     */
    protected $public_properties = [
        'id',
        'name',
        'default_text'
    ];
    
    /**
     * Unset properties
     * Use this for insert/update
     * @var array
     */
    protected $unset_properties = ['id'];
    
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

