<?php
namespace Core\Model;

interface RepositoryInterface
{
    /**
     * Return a set of all objects that we can iterate over.
     * Each entry should be a object instance.
     * @return Entity[]
     */
    public function fetchAll($params = []);
    
    /**
     * Return a single object.
     * @param  int $id Identifier of the object to return.
     * @return Object
     */
    public function fetch($id);
}

