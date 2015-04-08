<?php
// new categories blob field for use in standard eventlist module
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist']   = str_replace(';{protected_legend:hide}', ';{event_cat_legend:hide},event_categories;{protected_legend:hide}', $GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist']);
$GLOBALS['TL_DCA']['tl_module']['fields']['event_categories'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['event_categories'],
    'exclude'                 => true,
    'inputType'               => 'checkboxWizard',
    'foreignKey'              => 'tl_mae_event_cat.title',
    'eval'                    => array('tl_class'=>'clr', 'multiple'=>true, 'fieldType'=>'checkbox', 'foreignTable'=>'tl_mae_event_cat', 'titleField'=>'title', 'searchField'=>'title'),
    'sql'                     => "blob NULL"
);
?>