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
     * Delete Label
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
        
        try {
            return $this->command->delete($label);
        } catch (\Exception $ex) {
            $this->logger->critical(
                    sprintf('[Line:%d] - %s File: %s', __LINE__, 
                            $ex->getMessage(), __FILE__)
            );
            throw $ex;
        }
    }
}
