<?php
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['oncreate_callback'][] = array("MaeEventCategories\\MaeEventBe", "setDefaultCategories");

$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace(";{date_legend}", ";{cat_legend:hide},categories;{date_legend}", $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['categories'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['categories'],
    'exclude'                 => true,
    'filter'                  => true,
    'inputType'               => 'checkboxWizard',
    'foreignKey'              => 'tl_mae_event_cat.title',
    'eval'                    => array('tl_class'=>'clr', 'multiple'=>true, 'fieldType'=>'checkbox', 'foreignTable'=>'tl_mae_event_cat', 'titleField'=>'title', 'searchField'=>'title'),
    'sql'                     => "blob NULL"
);
?>