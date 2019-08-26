<?php

namespace DrinksAndCo\Experience\Ui\Component\Listing\Column;

class StoreName extends \Magento\Ui\Component\Listing\Columns\Column {
    protected $storeRepository;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        array $components = [],
        array $data = []
    ){
        $this->storeRepository = $storeRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource) {

        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as & $item) {
                $names = '';
                $ids = explode(',',$item['store_ids']);
                if(in_array(0,$ids)){
                    $names .= 'All Store Views';
                }else{
                    foreach ($ids as $id){
                        $names .= $this->storeRepository->getById($id)->getName().'<br>';
                    }
                }


                $item['store_ids'] = $names;
            }

        }
        return $dataSource;
    }
}