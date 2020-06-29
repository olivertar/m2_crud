<?php
namespace Orangecat\Crud\Model\Sample\Source;

use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{
    protected $crudSample;

    public function __construct(\Orangecat\Crud\Model\Sample $crudSample)
    {
        $this->crudSample = $crudSample;
    }

    public function toOptionArray()
    {
        $availableOptions = $this->crudSample->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
