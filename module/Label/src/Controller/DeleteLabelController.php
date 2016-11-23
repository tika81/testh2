<?php
namespace Label\Controller;

use Core\Model\CommandInterface;
use Core\Model\RepositoryInterface;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Psr\Log\LoggerInterface;

/**
 * Delete Label Controller
 * @author bojan
 */
class DeleteLabelController extends AbstractActionController
{
    /**
     * @var CommandInterface 
     */
    private $command;
    
    /**
     * @var RepositoryInterface 
     */
    private $repository;
    
    /**
     * Logger
     * @var LoggerInterface 
     */
    protected $logger;
    
    /**
     * @param CommandInterface $command
     * @param RepositoryInterface $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
            CommandInterface $command, 
            RepositoryInterface $repository,
            LoggerInterface $logger
    ) {
        $this->command = $command;
        $this->repository = $repository;
        $this->logger = $logger;
    }
    
    /**
     * Delete Action
     * @return ViewModel
     */
    public function deleteAction()
    {
        $id = intval($this->params()->fromRoute('id'));
        if (!$id) {
            $this->logger->error(sprintf(
                '[Line:%d] - Identifier \'id\' not found, file: %s', __LINE__, 
                    __FILE__
            ));
            return $this->redirect()->toRoute('label');
        }
        
        try {
            $label = $this->repository->fetch($id);
        } catch (InvalidArgumentException $ex) {
            $this->logger->error(
                sprintf('[Line:%d] - Not found, file: %s', __LINE__, __FILE__), 
                [$ex->getMessage()]
            );
            return $this->redirect()->toRoute('label');
        }
        
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return new ViewModel(['label' => $label]);
        }
        
        if ($id != $request->getPost('id') 
                || 'Delete' !== $request->getPost('confirm', 'no')) {
            return $this->redirect()->toRoute('label');
        }
        
        //delete label
        if (!$this->command->delete($label)) {
            $this->logger->critical(sprintf(
                '[Line:%d] - Delete action failed, file: %s', __LINE__, __FILE__
            ));
        }
        return $this->redirect()->toRoute('label');
    }
    
}

