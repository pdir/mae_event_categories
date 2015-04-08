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
$GLOBALS['TL_DCA']['tl_user']['palettes']['extend'] = str_replace('{calendars_legend}', '{calendars_legend},maeEventCat', $GLOBALS['TL_DCA']['tl_user']['palettes']['extend']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['custom'] = str_replace('{calendars_legend}', '{calendars_legend},maeEventCat', $GLOBALS['TL_DCA']['tl_user']['palettes']['custom']);

/**
 * Add a new field to tl_user
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['maeEventCat'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_user']['maeEventCat'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''"
);