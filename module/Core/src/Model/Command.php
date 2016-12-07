<?php
namespace Core\Model;

use RuntimeException;
use Zend\Db\Adapter\Driver\ResultInterface;
use Core\Model\CommandInterface;
use Core\Model\Entity;
use Core\Model\EntityInterface;
use Psr\Log\LoggerInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

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
     * Unset values from insert/
     * @var array
     */
    protected $unset_data = [];
    
    /**
     * @var TableGatewayInterface 
     */
    private $table_gateway;
    
    /**
     * Logger
     * @var LoggerInterface 
     */
    protected $logger;
    
    /**
     * @param TableGatewayInterface $table_gateway
     * @param LoggerInterface $logger
     */
    public function __construct(
            TableGatewayInterface $table_gateway,
            LoggerInterface $logger
    ) {
        $this->table_gateway = $table_gateway;
        $this->logger = $logger;
    }
    
    /**
     * Persist a new object or update an existing object in the system
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function save(EntityInterface $entity)
    {
        $identifier = $this->identifier;
        $id = $entity->$identifier;
        $data = $entity->toArray(true);
        if (!$id) {
            $this->table_gateway->insert($data);
            $id = $this->table_gateway->getLastInsertValue();
        } else {
            $this->table_gateway->update($data, [$identifier => $id]);
        }
        
        $entity->$identifier = $id;
        return $entity;
    }
    
    /**
     * Delete a Entity from the system.
     *
     * @param Entity $entity The Entity to delete.
     * @return bool
     */
    public function delete(EntityInterface $entity)
    {
        $identifier = $this->identifier;
        if (!$entity->$identifier) {
            $this->logger->error(sprintf(
                '[Line: %d] - Cannot delete Entity; missing identifier,'
                    . ' file: %s ', __LINE__, __FILE__)
            );
            throw RuntimeException('Cannot delete Entity; missing identifier');
        }
        
        return $this->table_gateway->delete([$identifier => $entity->$identifier]);
    }
}

