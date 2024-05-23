<?php

/*
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009 - 2019 terminal42 gmbh & Isotope eCommerce Workgroup
 *
 * @link       https://isotopeecommerce.org
 * @license    https://opensource.org/licenses/lgpl-3.0.html
 */

namespace IsotopeBcs\Backend\Product;


use Contao\Backend;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\StringUtil;
use Isotope\Model\Product;
use Isotope\DatabaseUpdater;

class BcsLabel extends \Label
{

    public function generate($row, $label, $dc, $args)
    {
      
        $objProduct = Product::findByPk($row['id']);

        foreach ($GLOBALS['TL_DCA'][$dc->table]['list']['label']['fields'] as $i => $field) {
            switch ($field) {
                case 'images':
                    $args[$i] = static::generateImage($objProduct);
                    break;

                case 'name':
                    $args[$i] = $this->generateName($row, $objProduct, $dc);
                    break;

                case 'price':
                    $args[$i] = $this->generatePrice($row);
                    break;

                case 'variantFields':
                    $args[$i] = $this->generateVariantFields($args[$i], $objProduct, $dc);
                    break;

                default:
                    $objProductType = ProductType::findByPk($row['type']);
                    if ($objProductType
                        && $objProductType->hasVariants()
                        && !\in_array($field, $objProductType->getAttributes(), true)
                        && \in_array($field, $objProductType->getVariantAttributes(), true)
                    ) {
                        $values = Database::getInstance()->prepare("SELECT $field FROM tl_iso_product WHERE pid=?")->execute($row['id'])->fetchEach($field);
                        $values = array_unique(array_filter($values));
                        $removed = array_splice($values, 3);

                        $args[$i] = implode('<br>', $values);

                        if ($removed) {
                            $args[$i] .= '<br><span title="'.StringUtil::specialchars(implode(', ', $removed)).'">…</span><span class="invisible">'.StringUtil::specialchars(implode(', ', $removed)).'</span>';
                        }
                    }
            }
        }

        return $args;
    }
}
