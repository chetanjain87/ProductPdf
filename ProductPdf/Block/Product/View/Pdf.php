<?php
/**
 * @category   Local
 * @package    Chetan_ProductPdf
 * @author     Chetan Jain @ Magento TEAM
 */

namespace Chetan\ProductPdf\Block\Product\View;

use Magento\Catalog\Block\Product\AbstractProduct;

class Pdf extends AbstractProduct
{
    const MEDIA_FOLDER_PATH = 'catalog/product/pdf';
    const ATTR_PDF_ONE = 'pdf';
    const ATTR_PDF_TWO = 'pdf_two';
    const ATTR_PDF_THREE = 'pdf_three';
    /**
     * @return null|string
     */
    public function getPdfFilePath($attr)
    {
        $pdf = $this->getProduct()->getData($attr);

        if($pdf)
        {
            $path = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . self::MEDIA_FOLDER_PATH . $pdf;
            return $path;
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getPdfFileName($attr)
    {
        $pdf = $this->getProduct()->getData($attr);

        if($pdf)
        {
            $file_name = substr($pdf, strrpos($pdf, '/') + 1);
            $file_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file_name);
            // $file_name = strlen($file_name) > 50 ? substr($file_name,0,50)."..." : $file_name;
            $file_name = str_replace(array('_', '-'), ' ', $file_name);

            return $file_name;
        }

        return null;
    }

    /**
     * @return string
     */
    public function downloadsAvailable()
    {
        $result = '';
        if ($this->getPdfFileName($this->getPdfOneAttrCode())) {
            $result = 'downloads-available';
        }
        if ($this->getPdfFileName($this->getPdfTwoAttrCode())) {
            $result = 'downloads-available';
        }
        if ($this->getPdfFileName($this->getPdfThreeAttrCode())) {
            $result = 'downloads-available';
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getPdfOneAttrCode() {
        return self::ATTR_PDF_ONE;
    }

    /**
     * @return string
     */
    public function getPdfTwoAttrCode() {
        return self::ATTR_PDF_TWO;
    }

    /**
     * @return string
     */
    public function getPdfThreeAttrCode() {
        return self::ATTR_PDF_THREE;
    }
}