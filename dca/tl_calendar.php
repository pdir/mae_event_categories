<?php
// new global operation calls categories from within calendar module (therefor the categories table has to be added to the calendar module in config.php)
array_insert($GLOBALS['TL_DCA']['tl_calendar']['list']['global_operations'], 1, array
(
    'mae_categories' => array
    (
        'label'               => &$GLOBALS['TL_LANG']['tl_calendar']['categories_label'],
		'href'                => 'do=calendar&table=tl_mae_event_cat',
        'class'               => 'header_new',
		'attributes'          => 'onclick="Backend.getScrollOffset()" style="padding-left: 22px;background-image: url(\'system/modules/mae_event_categories/assets/cat_icon.png\')"',
        'button_callback'     => array('tl_calendar_categories', 'buttonCategories')
    )
));

class tl_calendar_categories extends Backend
{
    /**
     * paint the categories global OP button
     */
    public function buttonCategories($href, $label, $title, $class, $attributes)
    {
        $this->import('BackendUser', 'User');

        if (!$this->User->isAdmin && !$this->User->maeEventCat) {
            return "";
        }
        else {
            return '<a href="'.$this->addToUrl($href).'" class="'.$class.'" title="'.specialchars($title).'"'.$attributes.'>'.$label.'</a> ';
        }
    }
}