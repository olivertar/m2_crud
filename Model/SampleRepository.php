<?php
namespace Orangecat\Crud\Model;

use Orangecat\Crud\Api\SampleRepositoryInterface;
use Orangecat\Crud\Api\Data;
use Orangecat\Crud\Model\ResourceModel\Sample as ResourceSample;
use Orangecat\Crud\Model\ResourceModel\Sample\CollectionFactory as SampleCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class SampleRepository implements SampleRepositoryInterface
{
    protected $resource;

    protected $sampleFactory;

    protected $sampleCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataSampleFactory;

    private $storeManager;

    private $collectionProcessor;

    public function __construct(
        ResourceSample $resource,
        SampleFactory $sampleFactory,
        \Orangecat\Crud\Api\Data\SampleInterfaceFactory $dataSampleFactory,
        SampleCollectionFactory $sampleCollectionFactory,
        Data\SampleSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->resource = $resource;
        $this->sampleFactory = $sampleFactory;
        $this->sampleCollectionFactory = $sampleCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataSampleFactory = $dataSampleFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    public function save(Data\SampleInterface $sample)
    {
        if (empty($sample->getStoreId())) {
            $sample->setStoreId($this->storeManager->getStore()->getId());
        }

        try {
            $this->resource->save($sample);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $sample;
    }

    public function getById($sampleId)
    {
        $sample = $this->sampleFactory->create();
        $this->resource->load($sample, $sampleId);
        if (!$sample->getId()) {
            throw new NoSuchEntityException(__('The sample with the "%1" ID doesn\'t exist.', $sampleId));
        }
        return $sample;
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $collection = $this->sampleCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    public function delete(Data\SampleInterface $sample)
    {
        try {
            $this->resource->delete($sample);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($sampleId)
    {
        return $this->delete($this->getById($sampleId));
    }

    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Orangecat\Crud\Model\Api\SearchCriteria\SampleCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}
