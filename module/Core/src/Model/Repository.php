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
     * Sort available fields
     * @var array
     */
    protected $sort_available_fields = [];
    
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
            LoggerInterface $logger
    ) {
        $this->sql = $sql;
        $this->select = $select;
        $this->result_set = $result_set;
        $this->logger = $logger;
    }
    
    /**
     * Fetch all hook
     * @param Select $select
     * @param array $params
     * @return Select $select
     */
    protected function fetchAllHook(Select $select, $params = [])
    {
        return $select;
    }
    
    /**
     * Fetch all order
     * @param Select $select
     * @param array $params
     * @return Select $select
     */
    protected function fetchAllOrder(Select $select, $params = [])
    {
        $sort_arr = [];
        $sort_params = (!empty($params['sort'])) 
                ? explode(',', $params['sort']) : [$this->identifier];
        foreach ($sort_params as $sort_field) {
            if ($sort_field[0] === '-' && in_array(substr($sort_field, 1), 
                    $this->sort_available_fields)) {
                $sort = substr($sort_field, 1);
                $order = ' DESC';
                $sort_arr[] = $sort . $order;
            } elseif (in_array($sort_field, $this->sort_available_fields)) {
                $sort_arr[] = $sort_field . ' ASC';
            }
        }
//        print_r($sort_arr);die;
        return $select->order($sort_arr);
    }
    
    /**
     * {@inheritDoc}
     */
    public function fetchAll($params = [])
    {
        $this->select = $this->fetchAllHook($this->select, $params);
        $this->select = $this->fetchAllOrder($this->select, $params);
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
            $this->logger->critical(sprintf(
                '[Line:%d] - Failed retrieving entity with identifier "%s"; '
                . 'unknown database error, file: %s', __LINE__, $id, __FILE__
            ));
            throw new \Zend\Db\ResultSet\Exception\RuntimeException(
                    sprintf('Failed retrieving entity with identifier "%s"; '
                    . 'unknown database error.', $id)
            );
        }
        
        $this->result_set->initialize($result);
        $entity = $this->result_set->current();
        if (!$entity) {
            $this->logger->error(sprintf(
                '[Line: %d] - Entity with identifier "%s" not found, file: %s ',
                    __LINE__, $id, __FILE__
            ));
            throw new InvalidArgumentException(sprintf(
                'Entity with identifier "%s" not found.',
                $id
            ));
        }
        
        return $entity;
    }
}

