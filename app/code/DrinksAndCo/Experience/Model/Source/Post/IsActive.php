<?php

namespace DrinksAndCo\Experience\Model\Source\Post;

use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{
    protected $postModel;

    public function __construct(\DrinksAndCo\Experience\Model\Post $postModel)
    {
        $this->postModel = $postModel;
    }

    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->postModel->getAvailableStatuses();

        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }

        return $options;
    }
}
