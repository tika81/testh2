<?php
namespace Core\Model;

use InvalidArgumentException;
use RuntimeException;
use Zend\Db\Sql\Select;
use Core\Model\RepositoryInterface;
use Psr\Log\LoggerInterface;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Core\Model\EntityInterface;

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
     * Prepares order for fetch all
     * @param array $params
     * @return array
     */
    protected function fetchAllOrder($params = [])
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
        return $sort_arr;
    }
    
    /**
     * Fetch all
     * @param array $params
     * @return Paginator
     */
    public function fetchAll($params = [])
    {
        $order  = $this->fetchAllOrder($params);
        $select = $this->fetchAllHook(
                $this->table_gateway->getSql()->select(), 
                $params
        );
        $select->order($order);
        
        $paginator_adapter = new DbSelect(
                $select,
                $this->table_gateway->getAdapter(),
                $this->table_gateway->getResultSetPrototype()
        );
        $paginator = new Paginator($paginator_adapter);
        $paginator->setCurrentPageNumber($params['page_number']);
        $paginator->setItemCountPerPage($params['page_size']);
        return $paginator;
    }
    
    /**
     * Fetch
     * @return EntityInterface
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function fetch($id)
    {
        $row = $this->table_gateway->select([$this->identifier . ' = ?' => $id]);
        $entity = $row->current();
        
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

