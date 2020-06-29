<?php
declare(strict_types=1);

namespace Orangecat\Crud\Model\Config\Source;

use Orangecat\Crud\Model\ResourceModel\Sample\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Block implements OptionSourceInterface
{
    private $options;

    private $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = $this->collectionFactory->create()->toOptionIdArray();
        }

        return $this->options;
    }
}
