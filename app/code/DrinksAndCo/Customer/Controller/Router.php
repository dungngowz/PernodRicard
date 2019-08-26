<?php

namespace DrinksAndCo\Customer\Controller;

use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Url;

class Router implements RouterInterface
{
    protected $actionFactory;
    protected $eventManager;
    protected $response;
    protected $dispatched;
    protected $storeManager;

    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager
    )
    {
        $this->actionFactory = $actionFactory;
        $this->eventManager = $eventManager;
        $this->response = $response;
        $this->storeManager = $storeManager;
    }

    public function match(RequestInterface $request)
    {
        // $info = $request->getPathInfo();
        
        // $params = ['dang-nhap', 'login'];

        // foreach($params as $item){
        //     if (preg_match("%^/(". $item .")(.*?)$%", $info, $m)) {
        //         $request->setPathInfo(str_replace($item, 'dccustomer/login', $info));
        //         return $this->actionFactory->create('Magento\Framework\App\Action\Forward', ['request' => $request]);
        //     }
        // }

        return null;
    }
}
