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
     * @param bool $unset
     * @return array
     */
    public function toArray($unset = false);
}

