<?php
namespace Orangecat\Crud\Controller\Adminhtml\Sample;

use Magento\Backend\App\Action\Context;
use Orangecat\Crud\Api\SampleRepositoryInterface as SampleRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Orangecat\Crud\Api\Data\SampleInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Orangecat_Crud::sample';

    protected $sampleRepository;

    protected $jsonFactory;

    public function __construct(
        Context $context,
        SampleRepository $sampleRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->sampleRepository = $sampleRepository;
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $sampleId) {
                    $sample = $this->sampleRepository->getById($sampleId);
                    try {
                        $sample->setData(array_merge($sample->getData(), $postItems[$sampleId]));
                        $this->sampleRepository->save($sample);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithSampleId(
                            $sample,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function getErrorWithSampleId(SampleInterface $sample, $errorText)
    {
        return '[Sample ID: ' . $sample->getId() . '] ' . $errorText;
    }
}
