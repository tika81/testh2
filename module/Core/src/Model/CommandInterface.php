<?php
namespace Core\Model;

/**
 * Command Interface
 * @author bojan
 */
interface CommandInterface
{
    /**
     * Persist a new object in the system.
     *
     * @param Entity $entity The object to insert; may or may not have 
     * an identifier.
     * @return Entity The inserted object, with identifier.
     */
    public function insert(Entity $entity);

    /**
     * Update an existing object in the system.
     *
     * @param Entity $entity The object to update; must have an identifier.
     * @return Entity The updated object.
     */
    public function update(Entity $entity);

    /**
     * Delete a object from the system.
     *
     * @param Entity $entity The object to delete.
     * @return bool
     */
    public function delete(Entity $entity);
}

