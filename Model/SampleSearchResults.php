<?php
declare(strict_types=1);

namespace Orangecat\Crud\Model;

use Orangecat\Crud\Api\Data\SampleSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class SampleSearchResults extends SearchResults implements SampleSearchResultsInterface
{
}
