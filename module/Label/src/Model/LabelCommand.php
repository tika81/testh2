<?php
namespace Label\Model;

use RuntimeException;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Core\Model\CommandInterface;
use Core\Model\Entity;

/**
 * Label Command
 * @author bojan 
 */
class LabelCommand implements CommandInterface
{
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
    
    public function __construct(
            Sql $sql,
            Insert $insert,
            Update $update,
            Delete $delete
    ) {
        $this->sql    = $sql;
        $this->insert = $insert;
        $this->update = $update;
        $this->delete = $delete;
    }

    /**
     * Persist a new label in the system.
     *
     * @param Label $label The label to insert; may or may not have an identifier.
     * @return Label The inserted label, with identifier.
     */
    public function insert(Entity $label)
    {
        $this->insert->values($label->toArray());
        
        $stmt = $this->sql->prepareStatementForSqlObject($this->insert);
        $result = $stmt->execute();
        
        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during label insert operation'
            );
        }
        
        $id = $result->getGeneratedValue();
        
        $label->id = $id;
        return $label;
    }

    /**
     * Update an existing label in the system.
     *
     * @param Label $label The label to update; must have an identifier.
     * @return Label The updated label.
     */
    public function update(Entity $label)
    {
        if (!$label->id) {
            throw RuntimeException('Cannot update label; missing identifier');
        }
        
        $this->update->set($label->toArray());
        $this->update->where(['id = ?' => $label->id]);
        
        $stmt = $this->sql->prepareStatementForSqlObject($this->update);
        $result = $stmt->execute();
        
        if (!$result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during label update operation'
            );
        }
        
        return $label;
    }

    /**
     * Delete a label from the system.
     *
     * @param Label $label The label to delete.
     * @return bool
     */
    public function delete(Entity $label)
    {
        if (!$label->id) {
            throw RuntimeException('Cannot delete label; missing identifier');
        }
        
        $this->delete->where(['id = ?' => $label->id]);
        
        $stmt = $this->sql->prepareStatementForSqlObject($this->delete);
        $result = $stmt->execute();
        
        if (!$result instanceof ResultInterface) {
            return false;
        }
        
        return true;
    }
}


