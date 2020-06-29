<?php
namespace Orangecat\Crud\Controller\Adminhtml\Sample;

use Magento\Framework\App\Action\HttpGetActionInterface;

class Edit extends \Orangecat\Crud\Controller\Adminhtml\Sample implements HttpGetActionInterface
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('sample_id');
        $model = $this->_objectManager->create(\Orangecat\Crud\Model\Sample::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This sample no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('crud_sample', $model);

        // 5. Build edit form
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Sample') : __('New Sample'),
            $id ? __('Edit Sample') : __('New Sample')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Samples'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Sample'));
        return $resultPage;
    }
}
