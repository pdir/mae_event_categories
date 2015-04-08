<?php

/**
 * Event Category Table
 *
 *
 * @package   MaeEventCategories
 * @author    Martin Eberhardt
 * @license   GNU/LGPL
 * @copyright Martin Eberhardt Webentwicklung & Photographie 2015
 */


/**
 * Table tl_mae_event_cat
 */
$GLOBALS['TL_DCA']['tl_mae_event_cat'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => false,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        ),
        'backlink'                    => 'do=calendar',
        'ondelete_callback'           => array(
            array('tl_mae_event_cat', 'checkPermission'),
            array('tl_mae_event_cat', 'onDelete')
        ),
        'onload_callback' => array
        (
            array('tl_mae_event_cat', 'checkPermission')
        ),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('title'),
            'flag'                    => 1,
            'panelLayout'             => 'search'
        ),
        'label' => array
        (
            'fields'                  => array('title'),
            'format'                  => '%s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_mae_event_cat']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_mae_event_cat']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_mae_event_cat']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_mae_event_cat']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Select
    'select' => array
    (
        'buttons_callback' => array()
    ),

    // Edit
    'edit' => array
    (
        'buttons_callback' => array()
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array(''),
        'default'                     => '{title_legend},title;'
    ),

    // Subpalettes
    'subpalettes' => array
    (
        ''                            => ''
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_mae_event_cat']['title'],
            'exclude'                 => false,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
            'sql'                     => "varchar(255) NOT NULL default ''"
        )
    )
);

class tl_mae_event_cat extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }


    /**
     * Check permissions to edit table tl_mae_event_cat
     */
    public function checkPermission()
    {
        if (!$this->User->isAdmin && !$this->User->maeEventCat)
        {
            $this->log('Not enough permissions to manage event categories', __METHOD__, TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }
    }


    /**
     * delete references in tl_calendar_events and tl_module
     */
    public function onDelete(DataContainer $dc)
    {
        if (!$dc->id)
        {
            return;
        }

        /*
         * delete references in tl_calendar_events
         */
        $objEvent = $this->Database->prepare("SELECT id, categories FROM tl_calendar_events WHERE categories != ''")
            ->execute();
        while($objEvent->next()) {
            $arrCats = unserialize($objEvent->categories);
            if(is_array($arrCats) && ($key = array_search($dc->id, $arrCats)) !== false) {
                unset($arrCats[$key]);
                $updCatVal = count($arrCats) > 0 ? serialize($arrCats) : "";
                $this->Database->prepare("UPDATE tl_calendar_events SET categories=? WHERE id=?")
                    ->execute(array($updCatVal, $objEvent->id));
            } // events having this category
        } // events having categories


        /*
         * delete references in tl_module
         */
        $objMod = $this->Database->prepare("SELECT id, event_categories FROM tl_module WHERE event_categories != ''")
            ->execute();
        while($objMod->next()) {
            $arrCats = unserialize($objMod->event_categories);
            if(is_array($arrCats) && ($key = array_search($dc->id, $arrCats)) !== false) {
                unset($arrCats[$key]);
                $updCatVal = count($arrCats) > 0 ? serialize($arrCats) : "";
                $this->Database->prepare("UPDATE tl_module SET event_categories=? WHERE id=?")
                    ->execute(array($updCatVal, $objMod->id));
            } // modules having this category
        } // modules having categories
    } // onDelete()
} // class tl_mae_event_cat