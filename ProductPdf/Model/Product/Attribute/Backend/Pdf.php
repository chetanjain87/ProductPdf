<?php
/**
 * @category   Local
 * @package    Chetan_ProductPdf
 * @author     Chetan Jain @ Magento TEAM
 */

namespace Chetan\ProductPdf\Model\Product\Attribute\Backend;

use Magento\Framework\App\Filesystem\DirectoryList;

class Pdf extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    const MEDIA_SUBFOLDER = 'catalog/product/pdf';

    protected $_uploaderFactory;
    protected $_filesystem;
    protected $_fileUploaderFactory;
    protected $_logger;

    /**
     * Construct
     *
     * @param \Psr\Log\LoggerInterface                $logger
     * @param \Magento\Framework\Filesystem           $filesystem
     * @param \Magento\Framework\File\UploaderFactory $uploaderFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\UploaderFactory $uploaderFactory
    ) {
        $this->_filesystem      = $filesystem;
        $this->_uploaderFactory = $uploaderFactory;
        $this->_logger          = $logger;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this
     * @throws \Exception
     * @throws \FrameworkException
     */
    public function afterSave($object)
    {
        $attributeName = $this->getAttribute()->getName();
        $fileName      = $this->uploadFileAndGetName($attributeName,
            $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath(self::MEDIA_SUBFOLDER));

        if ($fileName) {
            try {
                $object->setData($attributeName, $fileName);
                $this->getAttribute()->getEntity()->saveAttribute($object, $attributeName);
            } catch (\Exception $e) {
                throw $e;
            }

        }

        $value = $object->getData($attributeName . "_delete");
        if ($value == 'on') {
            $object->setData($attributeName, '');
            $this->getAttribute()->getEntity()->saveAttribute($object, $attributeName);
            return $this;
        }

        return $this;
    }

    /**
     * @param $input
     * @param $destinationFolder
     * @return string
     * @throws \FrameworkException
     */
    public function uploadFileAndGetName($input, $destinationFolder)
    {
        try {
            $uploader = $this->_uploaderFactory->create(array('fileId' => 'product[' . $input . ']'));
            $uploader->setAllowedExtensions(['pdf']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowCreateFolders(true);
            $uploader->save($destinationFolder);

            return $uploader->getUploadedFileName();
        } catch (\Exception $e) {
            if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
                throw $e;
            }

        }

        return '';

    }

}