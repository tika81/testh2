<?php
namespace Core\Model;

use RuntimeException;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Core\Model\CommandInterface;
use Core\Model\Entity;
use Psr\Log\LoggerInterface;

/**
 * Command mapper
 * @author bojan
 */
class Command implements CommandInterface
{
    /**
     * identifier
     * @var int|string
     */
    protected $identifier = 'id';
    
    /**
     * @var Sql 
     */
    private $sql;
    
    /**
     * @var Insert 
     */
    private $insert;
    
    /**
     * @var Update 
     */
    private $update;
    
    /**
     * @var Delete 
     */
    private $delete;
    
    /**
     * Logger
     * @var LoggerInterface 
     */
    protected $logger;
    
    /**
     * @param Sql $sql
     * @param Insert $insert
     * @param Update $update
     * @param Delete $delete
     * @param LoggerInterface $logger
     */
    public function __construct(
            Sql $sql,
            Insert $insert,
            Update $update,
            Delete $delete,
            LoggerInterface $logger
    ) {
        $this->sql    = $sql;
        $this->insert = $insert;
        $this->update = $update;
        $this->delete = $delete;
        $this->logger = $logger;
    }
    
     /**
     * Persist a new Entity in the system.
     *
     * @param Entity $entity The Entity to insert; may or may not have an identifier.
     * @return Entity The inserted Entity, with identifier.
     */
    public function insert(Entity $entity)
    {
        $identifier = $this->identifier;
        
        $this->insert->values($entity->toArray());
        $stmt = $this->sql->prepareStatementForSqlObject($this->insert);
        $result = $stmt->execute();
        
        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during entity insert operation'
            );
        }
        
        $id = $result->getGeneratedValue();
        
        $entity->$identifier = $id;
        return $entity;
    }

    /**
     * Update an existing Entity in the system.
     *
     * @param Entity $entity The Entity to update; must have an identifier.
     * @return Entity The updated Entity.
     */
    public function update(Entity $entity)
    {
        $identifier = $this->identifier;
        
        if (!$entity->$identifier) {
            throw RuntimeException('Cannot update Entity; missing identifier');
        }
        
        $this->update->set($entity->toArray());
        $this->update->where([$identifier . ' = ?' => $entity->$identifier]);
        
        $stmt = $this->sql->prepareStatementForSqlObject($this->update);
        $result = $stmt->execute();
        
        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during Entity update operation'
            );
        }
        
        return $entity;
    }

    /**
     * Delete a Entity from the system.
     *
     * @param Entity $entity The Entity to delete.
     * @return bool
     */
    public function delete(Entity $entity)
    {
        $identifier = $this->identifier;
        
        if (!$entity->$identifier) {
            throw RuntimeException('Cannot delete Entity; missing identifier');
        }
        
        $this->delete->where([$identifier . ' = ?' => $entity->$identifier]);
        
        $stmt = $this->sql->prepareStatementForSqlObject($this->delete);
        $result = $stmt->execute();
        
        if (!$result instanceof ResultInterface) {
            return false;
        }
        
        return true;
    }
}

