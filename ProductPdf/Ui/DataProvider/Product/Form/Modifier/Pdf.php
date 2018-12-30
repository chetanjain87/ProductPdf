<?php
/**
 * @category   Local
 * @package    Chetan_Productpdf
 * @author     Chetan Jain @ Magento TEAM
 */

namespace Chetan\ProductPdf\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;

/**
 * Data provider for "Pdf" field of product page
 */
class Pdf extends AbstractModifier
{
    /**
     * @param LocatorInterface $locator
     * @param UrlInterface     $urlBuilder
     * @param ArrayManager     $arrayManager
     */
    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
        ArrayManager $arrayManager
    ) {
        $this->locator      = $locator;
        $this->urlBuilder   = $urlBuilder;
        $this->arrayManager = $arrayManager;
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $fieldCodeArr = [
            'pdf',
            'pdf_two',
            'pdf_three'
        ];

        foreach ($fieldCodeArr as $fieldCode) {
            $elementPath   = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
            $containerPath = $this->arrayManager->findPath(static::CONTAINER_PREFIX . $fieldCode, $meta, null, 'children');

            if (!$elementPath) {
                return $meta;
            }

            $meta = $this->arrayManager->merge(
                $containerPath,
                $meta,
                [
                    'children' => [
                        $fieldCode => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'elementTmpl' => 'Chetan_ProductPdf/grid/filters/elements/pdf',
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            );
        }

        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }
}