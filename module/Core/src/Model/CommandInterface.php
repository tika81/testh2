<?php
namespace Core\Model;

use Core\Model\EntityInterface;

/**
 * Command Interface
 * @author bojan
 */
interface CommandInterface
{
    /**
     * Persist a new object or update an existing object in the system
     * @param EntityInterface $entity
     * @return $entity Returns identifier
     */
    public function save(EntityInterface $entity);
    
    /**
     * Delete a object from the system.
     *
     * @param Entity $entity The object to delete.
     * @return bool
     */
    public function delete(EntityInterface $entity);
}

