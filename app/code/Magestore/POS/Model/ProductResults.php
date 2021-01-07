<?php


namespace Magestore\POS\Model;


use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class ProductResults implements \Magestore\POS\Api\Data\ProductResultsInterface
{

    public $parentId;

    public $qty;

    public $currency_symbol;

    public $currency_code;

    /**
     * @inheritDoc
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @inheritDoc
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @inheritDoc
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @inheritDoc
     */
    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getCurrencySymbol()
    {
        return $this->currency_symbol;
    }

    /**
     * @inheritDoc
     */
    public function setCurrencySymbol($currencySymbol)
    {
       $this->currency_symbol = $currencySymbol;
    }

    /**
     * @inheritDoc
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * @inheritDoc
     */
    public function setCurrencyCode($code)
    {
        $this->currency_code = $code;
    }
}
