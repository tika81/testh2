<?php
namespace Core\Resource;

use Core\Model\RepositoryInterface;
use Psr\Log\LoggerInterface;
use Core\Resource\ListResourceInterface;
use Zend\Stdlib\RequestInterface;
use Zend\Paginator\Paginator;

/**
 * List Resource
 * @author bojan
 */
class ListResource implements ListResourceInterface
{
    /**
     * @var RequestInterface 
     */
    protected $request;
    
    /**
     * @var RepositoryInterface 
     */
    protected $repository;
    
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    /**
     * @param RepositoryInterface $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
            RequestInterface $request,
            RepositoryInterface $repository,
            LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->repository = $repository;
        $this->logger = $logger;
    }
    
    /**
     * Return a set of all objects that we can iterate over.
     * Each entry should be a object instance.
     * @return Paginator
     */
    public function fetchAll()
    {
        $query_params = $this->request->getQuery();
        $q    = (!empty($query_params['q'])) ? $query_params['q'] : '';
        $sort = (!empty($query_params['sort'])) ? $query_params['sort'] : '';
        $page_number = (!empty($query_params['page_number'])) 
                ? $query_params['page_number'] : 1;
        $page_size = (!empty($query_params['page_size'])) 
                ? $query_params['page_size'] : 10;
        $params = [
            'q' => $q,
            'sort' => $sort,
            'page_number' => $page_number,
            'page_size' => $page_size,
        ];
        return $this->repository->fetchAll($params);
    }
    
    /**
     * Return a single object.
     * @param  int $id Identifier of the object to return.
     * @return Object
     */
    public function fetch($id)
    {
        try {
            return $this->repository->fetch($id);
        } catch (\InvalidArgumentException $ex) {
            $this->logger->error(sprintf(
                '[Line:%d] - %s File: %s', __LINE__, $ex->getMessage(), __FILE__
            ));
            //return error here
            return $ex->getMessage();
        }
    }
}
