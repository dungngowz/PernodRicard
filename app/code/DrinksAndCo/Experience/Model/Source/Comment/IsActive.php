<?php

namespace DrinksAndCo\Experience\Model\Source\Comment;

use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{
    protected $commentModel;

    public function __construct(\DrinksAndCo\Experience\Model\Comment $commentModel)
    {
        $this->commentModel = $commentModel;
    }

    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->commentModel->getAvailableStatuses();

        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }

        return $options;
    }
}
