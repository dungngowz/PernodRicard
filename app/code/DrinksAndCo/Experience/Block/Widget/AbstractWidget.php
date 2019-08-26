<?php

namespace DrinksAndCo\Experience\Block\Widget;

class AbstractWidget extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_experienceHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \DrinksAndCo\Experience\Helper\Data $experienceHelper,
        array $data = []
    )
    {
        $this->_experienceHelper = $experienceHelper;
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
