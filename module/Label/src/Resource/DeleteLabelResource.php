<?php
namespace Label\Resource;

use Core\Resource\DeleteResource;
use Core\Model\EntityInterface;

/**
 * Description of DeleteLabelResource
 *
 * @author TikaLT
 */
class DeleteLabelResource extends DeleteResource
{
    /**
     * 
     * @param integer $id
     * @return boolean|string|array
     */
    public function delete($id) 
    {
        $label = $this->list_resource->fetch($id);
        if (!$label instanceof EntityInterface) {
            $this->logger->error(
                sprintf('[Line:%d] - %s File: %s', __LINE__, $label, __FILE__)
            );
            return $label;
        }
        
        $view_model_arr = ['label' => $label];
        if (!$this->request->isPost()) {
            return $view_model_arr;
        }
        
        if ($id != $this->request->getPost('id') 
                || 'Delete' !== $this->request->getPost('confirm', 'no')) {
            return false;
        }
        
        //delete label
        if (!$this->command->delete($label)) {
            $this->logger->critical(
                    sprintf('[Line:%d] - Delete action failed, file: %s', 
                            __LINE__, __FILE__)
            );
            return false;
        }
    }
}
