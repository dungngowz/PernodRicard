<?php

namespace DrinksAndCo\History\Block\Widget;

class AbstractWidget extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_historyHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \DrinksAndCo\History\Helper\Data $historyHelper,
        array $data = []
    )
    {
        $this->_historyHelper = $historyHelper;
        parent::__construct($context, $data);
    }

    public function getConfig($key, $default = '')
    {
        if ($this->hasData($key)) {
            return $this->getData($key);
        }
        return $default;
    }
}
