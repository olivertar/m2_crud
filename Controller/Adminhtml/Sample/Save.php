<?php
namespace Orangecat\Crud\Controller\Adminhtml\Sample;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Orangecat\Crud\Api\SampleRepositoryInterface;
use Orangecat\Crud\Model\Sample;
use Orangecat\Crud\Model\SampleFactory;

class Save extends \Orangecat\Crud\Controller\Adminhtml\Sample implements HttpPostActionInterface
{
    protected $dataPersistor;

    private $sampleFactory;

    private $sampleRepository;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        SampleFactory $sampleFactory = null,
        SampleRepositoryInterface $sampleRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->sampleFactory = $sampleFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(SampleFactory::class);
        $this->sampleRepository = $sampleRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(SampleRepositoryInterface::class);
        parent::__construct($context, $coreRegistry);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Sample::STATUS_ENABLED;
            }
            if (empty($data['sample_id'])) {
                $data['sample_id'] = null;
            }

            $model = $this->sampleFactory->create();

            $id = $this->getRequest()->getParam('sample_id');
            if ($id) {
                try {
                    $model = $this->sampleRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This sample no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
                $data['image'] = $data['image'][0]['name'];
            } elseif (isset($data['image'][0]['name']) && !isset($data['image'][0]['tmp_name'])) {
                $data['image'] =$data['image'][0]['name'];
            } else {
                $data['image'] = null;
            }

            $model->setData($data);

            try {
                $this->sampleRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the sample.'));
                $this->dataPersistor->clear('crud_sample');
                return $this->processSampleReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the sample.'));
            }

            $this->dataPersistor->set('crud_sample', $data);
            return $resultRedirect->setPath('*/*/edit', ['sample_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    private function processSampleReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['sample_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $duplicateModel = $this->sampleFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $duplicateModel->setIsActive(Sample::STATUS_DISABLED);
            $this->sampleRepository->save($duplicateModel);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the sample.'));
            $this->dataPersistor->set('crud_sample', $data);
            $resultRedirect->setPath('*/*/edit', ['sample_id' => $id]);
        }
        return $resultRedirect;
    }
}
