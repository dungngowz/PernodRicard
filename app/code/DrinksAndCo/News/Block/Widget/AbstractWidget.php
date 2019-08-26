<?php

namespace DrinksAndCo\News\Block\Widget;

class AbstractWidget extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_newsHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \DrinksAndCo\News\Helper\Data $newsHelper,
        array $data = []
    )
    {
        $this->_newsHelper = $newsHelper;
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
