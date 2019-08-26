<?php

namespace DrinksAndCo\Blog\Block\Adminhtml;

class ContentType extends \Magento\Config\Block\System\Config\Form\Field {

    /**
     * Form element which re-rendering
     *
     * @var \Magento\Framework\Data\Form\Element\Fieldset
     */
    protected $_element;

    /**
     * @var string
     */
    protected $_template = 'Magento_Backend::store/switcher/form/renderer/fieldset/element.phtml';

    /**
     * Retrieve an element
     *
     * @return \Magento\Framework\Data\Form\Element\Fieldset
     */
    public function getElement()
    {
        return $this->_element;
    }

    /**
     * Render element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $value = $element->getData('value');

        $this->_element = $element;
        $html = $this->toHtml();

        $html .= '<script type="text/javascript">
            require(["jquery"], function ($) {
                $(document).ready(function () {
                    
                    var $el = $("#' . $element->getHtmlId() . '");
                    $el.on("change", function(){
                        
                        $.ajax({
                           type : \'POST\',
                           url : "https://magento.test/blog/category/filterbycontenttype",
                           data: {
                               contentType: $(this).val()
                           },
                           success: function(res){
                               console.log(res);
                               $("#post_general_category_id").html(res.msg);
                           }
    
                       });
                            
                    }); 
                });
            });
            </script>';

        return $html;

    }

}