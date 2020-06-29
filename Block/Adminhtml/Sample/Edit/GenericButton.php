<?php
namespace Orangecat\Crud\Block\Adminhtml\Sample\Edit;

use Magento\Backend\Block\Widget\Context;
use Orangecat\Crud\Api\SampleRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    protected $context;

    protected $sampleRepository;

    public function __construct(
        Context $context,
        SampleRepositoryInterface $sampleRepository
    ) {
        $this->context = $context;
        $this->sampleRepository = $sampleRepository;
    }

    public function getSampleId()
    {
        try {
            return $this->sampleRepository->getById(
                $this->context->getRequest()->getParam('sample_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
