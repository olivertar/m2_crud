<?php
namespace Orangecat\Crud\Block\Adminhtml;

class Sample extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'Orangecat_Crud';
        $this->_controller = 'adminhtml_sample';
        $this->_headerText = __('Sample');
        $this->_addButtonLabel = __('Add New Sample');
        parent::_construct();
    }
}
