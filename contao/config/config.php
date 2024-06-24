<?php

use Pdir\MaeEventCategoriesBundle\Controller\FrontendModule\ModuleFilter;
use Pdir\MaeEventCategoriesBundle\Model\MaeEventCatModel;
/*
 * MODULES
 */

/* -- check in, if you want a separate module
$eventModAr = array (
    'MaeEventCat' => array (
        'tables' => array('tl_mae_event_cat'),
        'icon' => 'system/modules/mae_event_categories/assets/cat_icon.png'
    )
);
array_insert($GLOBALS['BE_MOD']['content'], 2, $eventModAr);
*/

// allow categories table in calendar module
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_mae_event_cat';

/**
 * Add permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'maeEventCat';
$GLOBALS['TL_PERMISSIONS'][] = 'maeEventCatDefault';

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['events']['mae_event_filter'] = ModuleFilter::class;

/*
 * Models
 */
$GLOBALS['TL_MODELS']['tl_mae_event_cat'] = MaeEventCatModel::class;
