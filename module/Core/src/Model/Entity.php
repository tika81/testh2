<?php
namespace Core\Model;

/**
 * Entity
 * @author bojan
 */
class Entity implements EntityInterface
{
    protected $public_properties = [];
    
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
    public function toArray()
    {
        $data = [];
        foreach ($this->public_properties as $public_property) {
            $data[$public_property] = (!empty($this->$public_property)) 
                    ? $this->$public_property : null;
        }
        
        return $data;
    }
}

