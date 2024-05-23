<?php

use IsotopeBcs\Backend\Product\LabelBcs;

$table = Isotope\Model\Product::getTable();

$GLOBALS['TL_DCA'][$table]['list']['label']['label_callback.default'] = $GLOBALS['TL_DCA'][$table]['list']['label']['label_callback'];
$GLOBALS['TL_DCA'][$table]['list']['label']['label_callback'] = [LabelBcs::class, 'generate'];
