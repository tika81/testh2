<?php
namespace Core\Model;

/**
 * Entity
 * @author bojan
 */
class Entity implements EntityInterface
{
    /**
     * Public properties
     * @var array
     */
    protected $public_properties = [];
    
    /**
     * Unset properties
     * Use this for insert/update
     * @var array
     */
    protected $unset_properties = [];
    
    /**
     * Exchange array
     * @param array $data
     */
    public function exchangeArray($data = [])
    {
        foreach ($this->public_properties as $public_property) {
            $this->$public_property = (!empty($data[$public_property])) 
                    ? $data[$public_property] : null;
        }
    }
    
    /**
     * Returns array copy of object
     * @return array
     */
    public function toArray($unset = false)
    {
        $data = [];
        foreach ($this->public_properties as $public_property) {
            if ($unset && in_array($public_property, $this->unset_properties)) {
                continue;
            }
            
            $data[$public_property] = (!empty($this->$public_property)) 
                    ? $this->$public_property : null;
        }
        
        return $data;
    }
}

