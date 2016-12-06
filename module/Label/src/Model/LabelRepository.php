<?php
namespace Label\Model;

use Core\Model\Repository;
use Zend\Db\Sql\Select;

/**
 * Label Repository
 * @author bojan
 */
class LabelRepository extends Repository
{
    /**
     * Sort available fields
     * @var array
     */
    protected $sort_available_fields = [
        'id', 
        'name',
        'default_text',
    ];
    
    /**
     * Fetch all hook
     * @param Select $select
     * @param type $params
     * @return Select $select
     */
    protected function fetchAllHook(Select $select, $params = [])
    {
        if (!empty($params['q'])) {
            $select->where->like('name', '%' . $params['q'] . '%')
                ->or->like('default_text',  '%' . $params['q'] . '%');
        }
        
        return $select;
    }
}
