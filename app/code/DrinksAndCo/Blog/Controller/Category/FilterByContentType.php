<?php

namespace DrinksAndCo\Blog\Controller\Category;


class FilterByContentType extends \Magento\Framework\App\Action\Action
{

    protected $_categoryModel;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \DrinksAndCo\Blog\Model\Category $categoryModel,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        $this->_categoryModel = $categoryModel;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $params = $this->getRequest()->getPost();
        $content_type = $params['contentType'];

        $collections = $this->_categoryModel->getCollection()->addFieldToFilter('content_type', $content_type)->toArray();

        $html = '';

        if($collections['totalRecords'] == 0){
            $html = '<option>-----</option>';
        }else{
            $items = $collections['items'];
            foreach ($items as $item){
                $html .= '<option value="'.$item['category_id'].'">'.$item['title'].'</option>';
            }
        }

        $response = ['code' => 200, 'msg' => $html, 'params' => $params ];

        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData($response);
        return $resultJson;
    }
}
