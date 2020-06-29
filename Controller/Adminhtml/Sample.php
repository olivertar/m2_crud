<?php
namespace Orangecat\Crud\Controller\Adminhtml;

abstract class Sample extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Orangecat_Crud::sample';

    protected $_coreRegistry;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry)
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Orangecat_Crud::crud_sample')
            ->addBreadcrumb(__('Crud'), __('Crud'))
            ->addBreadcrumb(__('Samples'), __('Samples'));
        return $resultPage;
    }
}
