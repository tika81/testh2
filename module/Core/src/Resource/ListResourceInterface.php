<?php
namespace Core\Resource;

/**
 * List Resource Interface
 * @author bojan
 */
interface ListResourceInterface 
{
    /**
     * Return a set of all objects that we can iterate over.
     * Each entry should be a object instance.
     * @return Entity[]
     */
    public function fetchAll();
    
    /**
     * Return a single object.
     * @param  int $id Identifier of the object to return.
     * @return Object
     */
    public function fetch($id);
}
