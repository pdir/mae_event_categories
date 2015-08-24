<?php
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
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = "tl_mae_event_cat";


/*
 * HOOKS
 */
$GLOBALS['TL_HOOKS']['getAllEvents'][] = array('MaeEventCategories\MaeEvent', 'getAllEvents');

/**
 * Add permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'maeEventCat';

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['events']['mae_event_filter'] = 'MaeEventCategories\ModuleFilter';
