<?php
/**
 * @category   Local
 * @package    Chetan_ProductPdf
 * @author     Chetan Jain @ Magento TEAM
 */

namespace Chetan\ProductPdf\Model\Product\Attribute\Frontend;

class Pdf extends \Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend
{

    const MEDIA_SUBFOLDER = 'catalog/product/pdf';
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Construct
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->_storeManager = $storeManager;
    }

    /**
     * Returns url to product pdf
     *
     * @param  \Magento\Catalog\Model\Product $product
     *
     * @return string|false
     */
    public function getUrl($product)
    {
        $pdf = $product->getData($this->getAttribute()->getAttributeCode());
        $url = FALSE;
        if(!empty($pdf))
        {
            $url = $this->_storeManager->getStore($product->getStore())
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                . self::MEDIA_SUBFOLDER . $pdf;
        }
        return $url;
    }
}