<?php
namespace Orangecat\Crud\Controller\Adminhtml\Sample;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;

class Upload extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    protected $baseTmpPath;

    protected $imageUploader;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Orangecat\Crud\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Orangecat_Crud::sample');
    }

    public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'image');

        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
