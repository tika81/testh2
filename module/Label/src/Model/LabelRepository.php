<?php
namespace Label\Model;

use InvalidArgumentException;
use RuntimeException;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Core\Model\RepositoryInterface;

class LabelRepository implements RepositoryInterface
{
    /**
     * @var Sql 
     */
    private $sql;
    
    /**
     * @var HydratingResultSet 
     */
    private $result_set;
    
    public function __construct(Sql $sql, HydratingResultSet $result_set)
    {
        $this->sql = $sql;
        $this->result_set = $result_set;
    }
    
    /**
     * {@inheritDoc}
     */
    public function fetchAll()
    {
        $select = $this->sql->select('ht_label');
        $stmt = $this->sql->prepareStatementForSqlObject($select);
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
        $select = $this->sql->select('ht_label');
        $select->where(['id = ?' => $id]);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        if (!$result instanceof ResultInterface || !$result->isQueryResult()) {
            throw new \Zend\Db\ResultSet\Exception\RuntimeException(sprintf(
                'Failed retrieving label with identifier "%s"; unknown database error.',
                $id
            ));
        }
        
        $this->result_set->initialize($result);
        $label = $this->result_set->current();
        if (!$label) {
            throw new InvalidArgumentException(sprintf(
                'Label with identifier "%s" not found.',
                $id
            ));
        }
        
        return $label;
    }
}

