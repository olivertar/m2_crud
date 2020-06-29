<?php
namespace Orangecat\Crud\Model\ResourceModel\Sample\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Orangecat\Crud\Api\Data\SampleInterface;
use Orangecat\Crud\Model\ResourceModel\Sample;
use Magento\Framework\EntityManager\MetadataPool;

class SaveHandler implements ExtensionInterface
{
    protected $metadataPool;

    protected $resourceSample;

    public function __construct(
        MetadataPool $metadataPool,
        Sample $resourceSample
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceSample = $resourceSample;
    }

    public function execute($entity, $arguments = [])
    {
        $entityMetadata = $this->metadataPool->getMetadata(SampleInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $connection = $entityMetadata->getEntityConnection();

        $oldStores = $this->resourceSample->lookupStoreIds((int)$entity->getId());
        $newStores = (array)$entity->getStores();

        $table = $this->resourceSample->getTable('crud_sample_store');

        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'store_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }

        return $entity;
    }
}
