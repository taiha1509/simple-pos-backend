<?php
namespace Magestore\POS\Api\Data;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;

interface ProductResultsInterface
{
    /**
     * @return int
     */
    public function getParentId();

    /**
     * @param int $parentId
     * @return void
     */
    public function setParentId($parentId);

    /**
     * @return int
     */
    public function getQty();

    /**
     * @param int $qty
     * @return void
     */
    public function setQty($qty);

    /**
     * @return string
     */
    public function getCurrencySymbol();

    /**
     * @param string $currencySymbol
     * @return void
     */
    public function setCurrencySymbol($currencySymbol);

    /**
     * @return int
     */
    public function getCurrencyCode();

    /**
     * @param int $code
     * @return void
     */
    public function setCurrencyCode($code);
}
