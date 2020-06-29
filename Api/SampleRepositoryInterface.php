<?php
namespace Orangecat\Crud\Api;

interface SampleRepositoryInterface
{
    /**
     * Save sample.
     *
     * @param \Orangecat\CrudApi\Data\SampleInterface $block
     * @return \Orangecat\Crud\Api\Data\SampleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\SampleInterface $sample);

    /**
     * Retrieve sample.
     *
     * @param int $blockId
     * @return \Orangecat\Crud\Api\Data\SampleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($sampleId);

    /**
     * Retrieve samples matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Orangecat\Crud\Api\Data\SampleSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete sample.
     *
     * @param \Orangecat\Crud\Api\Data\SampleInterface $block
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\SampleInterface $sample);

    /**
     * Delete sample by ID.
     *
     * @param int $sampleId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($sampleId);
}
