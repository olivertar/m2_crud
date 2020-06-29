<?php
namespace Orangecat\Crud\Controller\Adminhtml\Sample;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Delete extends \Orangecat\Crud\Controller\Adminhtml\Sample implements HttpPostActionInterface
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('sample_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Orangecat\Crud\Model\Sample::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the sample.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['sample_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a sample to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
