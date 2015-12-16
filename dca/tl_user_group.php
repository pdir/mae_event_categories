<?php

/**
 * Group settings
 *
 *
 * @package   MaeEventCategories
 * @author    Martin Eberhardt
 * @license   GNU/LGPL
 * @copyright Martin Eberhardt Webentwicklung & Photographie 2015
 */

/**
 * Load tl_user language file
 */
\System::loadLanguageFile('tl_user');

/**
 * Add a palette to tl_user_group
 */
$GLOBALS['TL_DCA']['tl_user_group']['palettes']['default'] = str_replace('{calendars_legend}', '{mae_evt_cat_legend},maeEventCat,maeEventCatDefault;{calendars_legend}', $GLOBALS['TL_DCA']['tl_user_group']['palettes']['default']);

/**
 * Add a new field to tl_user_group
 */
$GLOBALS['TL_DCA']['tl_user_group']['fields']['maeEventCat'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_user']['maeEventCat'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_user_group']['fields']['maeEventCatDefault'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_user']['default_event_categories'],
    'exclude'                 => true,
    'inputType'               => 'checkboxWizard',
    'foreignKey'              => 'tl_mae_event_cat.title',
    'eval'                    => array('tl_class'=>'clr', 'multiple'=>true, 'fieldType'=>'checkbox', 'foreignTable'=>'tl_mae_event_cat', 'titleField'=>'title', 'searchField'=>'title'),
    'sql'                     => "blob NULL"
);