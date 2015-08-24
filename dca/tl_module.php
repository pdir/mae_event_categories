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

$GLOBALS['TL_DCA']['tl_module']['palettes']['mae_event_filter'] = '{title_legend},name,type;{mae_setup_legend},mae_event_list,headline;{event_cat_legend:hide},event_categories';
$GLOBALS['TL_DCA']['tl_module']['fields']['mae_event_list'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['mae_event_list'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_module.name',
    'eval'                    => array('chosen'=>true, 'tl_class'=>'w50','mandatory'=>false),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
    'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
);
?>