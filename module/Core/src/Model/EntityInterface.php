<?php
namespace Core\Model;

/**
 * Entity Interface
 * @author bojan
 */
interface EntityInterface
{
    /**
     * Exchange Array
     * @param array $data
     */
    public function exchangeArray($data = []);
    
    /**
     * Returns array copy of object
     * @return array
     */
    public function toArray();
}

