<?php

/**
 * User settings
 *
 *
 * @package   MaeEventCategories
 * @author    Martin Eberhardt
 * @license   GNU/LGPL
 * @copyright Martin Eberhardt Webentwicklung & Photographie 2015
 */

/**
 * Add a palette to tl_user
 */
$GLOBALS['TL_DCA']['tl_user']['palettes']['extend'] = str_replace('{calendars_legend}', '{mae_evt_cat_legend},maeEventCat,maeEventCatDefault;{calendars_legend}', $GLOBALS['TL_DCA']['tl_user']['palettes']['extend']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['custom'] = str_replace('{calendars_legend}', '{mae_evt_cat_legend},maeEventCat,maeEventCatDefault;{calendars_legend}', $GLOBALS['TL_DCA']['tl_user']['palettes']['custom']);

/**
 * Add a new field to tl_user
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['maeEventCat'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_user']['maeEventCat'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user']['fields']['maeEventCatDefault'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_user']['default_event_categories'],
    'exclude'                 => true,
    'inputType'               => 'checkboxWizard',
    'foreignKey'              => 'tl_mae_event_cat.title',
    'eval'                    => array('tl_class'=>'clr', 'multiple'=>true, 'fieldType'=>'checkbox', 'foreignTable'=>'tl_mae_event_cat', 'titleField'=>'title', 'searchField'=>'title'),
    'sql'                     => "blob NULL"
);
