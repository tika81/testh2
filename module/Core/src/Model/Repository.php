<?php
namespace Core\Model;

use InvalidArgumentException;
use RuntimeException;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Core\Model\RepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Repository mapper
 */
class Repository implements RepositoryInterface 
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
     * @var HydratingResultSet 
     */
    private $result_set;
    
    /**
     * Logger
     * @var LoggerInterface 
     */
    protected $logger;
    
    /**
     * @param Sql $sql
     * @param Select $select
     * @param HydratingResultSet $result_set
     * @param LoggerInterface $logger
     */
    public function __construct(
            Sql $sql, 
            Select $select, 
            HydratingResultSet $result_set, 
            LoggerInterface $logger) {
        $this->sql = $sql;
        $this->select = $select;
        $this->result_set = $result_set;
        $this->logger = $logger;
    }
    
    /**
     * {@inheritDoc}
     */
    public function fetchAll()
    {
        $stmt = $this->sql->prepareStatementForSqlObject($this->select);
        $result = $stmt->execute();
        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            return [];
        }
        
        $this->result_set->initialize($result);
        return $this->result_set;
    }
    
    /**
     * {@inheritDoc}
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function fetch($id)
    {
        $this->select->where([$this->identifier . ' = ?' => $id]);
        
        $statement = $this->sql->prepareStatementForSqlObject($this->select);
        $result = $statement->execute();
        
        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new \Zend\Db\ResultSet\Exception\RuntimeException(sprintf(
                'Failed retrieving entity with identifier "%s"; unknown database error.',
                $id
            ));
        }
        
        $this->result_set->initialize($result);
        $entity = $this->result_set->current();
        if (!$entity) {
            throw new InvalidArgumentException(sprintf(
                'Entity with identifier "%s" not found.',
                $id
            ));
        }
        
        return $entity;
    }
}

