<?php

namespace DrinksAndCo\History\Model\Source\Post;

use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{
    protected $postModel;

    public function __construct(\DrinksAndCo\History\Model\Post $postModel)
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
