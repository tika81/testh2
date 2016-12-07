<?php
namespace Label\Resource;

use Core\Resource\WriteResource;
use Core\Model\EntityInterface;

/**
 * Write Label Resource
 * @author TikaLT
 */
class WriteLabelResource extends WriteResource
{
    /**
     * Inserts new label
     * @return array|EntityInterface
     * @throws \Exception
     */
    public function add()
    {
        $view_model_arr = ['form' => $this->form];
        
        if (!$this->request->isPost()) {
            return $view_model_arr;
        }
        
        $this->form->setData($this->request->getPost());
        if (!$this->form->isValid()) {
            return $view_model_arr;
        }
        
        $label = $this->form->getData();
        try {
            return $this->command->save($label);
        } catch (\Exception $ex) {
            $this->logger->critical(
                    sprintf('[Line:%d] - %s File: %s', __LINE__, 
                            $ex->getMessage(), __FILE__)
            );
            throw $ex;
        }
    }
    
    /**
     * Updates label
     * @param integer $id
     * @return EntityInterface|string|array
     * @throws \Exception
     */
    public function edit($id)
    {
        $label = $this->list_resource->fetch($id);
        if (!$label instanceof EntityInterface) {
            $this->logger->error(
                sprintf('[Line:%d] - %s File: %s', __LINE__, $label, __FILE__)
            );
            return $label;
        }
        
        $this->form->bind($label);
        $view_model_arr = ['form' => $this->form];
        if (!$this->request->isPost()) {
            return $view_model_arr;
        }
        
        $this->form->setData($this->request->getPost());
        if (!$this->form->isValid()) {
            return $view_model_arr;
        }
        
        try {
            return $this->command->save($label);
        } catch (\Exception $ex) {
            $this->logger->critical(sprintf(
                '[Line:%d] - %s File: %s', __LINE__, $ex->getMessage(), __FILE__
            ));
            throw $ex;
        }
    }
}
