<?php
namespace Orangecat\Crud\Model\Sample;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Orangecat\Crud\Model\ResourceModel\Sample\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    protected $collection;

    protected $dataPersistor;

    protected $loadedData;

    public $_storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $sampleCollectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $sampleCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $media_url =  $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'sampleimages/';
        foreach ($items as $sample) {
            $this->loadedData[$sample->getId()] = $sample->getData();
            if ($sample->getImage()) {
                $m['image'][0]['name'] = $sample->getImage();
                $m['image'][0]['url'] = $media_url . $sample->getImage();
                $fullData = $this->loadedData;
                $this->loadedData[$sample->getId()] = array_merge($fullData[$sample->getId()], $m);
            }
        }

        $data = $this->dataPersistor->get('crud_sample');
        if (!empty($data)) {
            $sample = $this->collection->getNewEmptyItem();
            $sample->setData($data);
            $this->loadedData[$sample->getId()] = $sample->getData();
            $this->dataPersistor->clear('crud_sample');
        }

        return $this->loadedData;
    }
}
