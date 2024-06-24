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

use Contao\Backend;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\StringUtil;
use Contao\System;

/**
 * Table tl_mae_event_cat
 */
$GLOBALS['TL_DCA']['tl_mae_event_cat'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => DC_Table::class,
        'enableVersioning'            => false,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'alias' => 'index'
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
                'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
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
        'default'                     => '{title_legend},title,alias;{layout_legend},cssId,cssClass'
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
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_mae_event_cat']['alias'],
            'exclude'                 => false,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>50, 'rgxp'=>'alias', 'tl_class'=>'w50', 'unique'=>true),
            'save_callback'           => array(
                array('tl_mae_event_cat', 'generateAlias')
            ),
            'sql'                     => "varchar(50) NOT NULL default ''"
        ),
        'cssId' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_mae_event_cat']['cssId'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>60, 'tl_class'=>'w50'),
            'sql'                     => "varchar(60) NOT NULL default ''"
        ),
        'cssClass' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_mae_event_cat']['cssClass'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
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
        $this->import('Contao\BackendUser', 'User');
    }


    /**
     * Check permissions to edit table tl_mae_event_cat
     */
    public function checkPermission(): void
    {
        if (!$this->User->isAdmin && !$this->User->maeEventCat)
        {
            System::getContainer()->get('monolog.logger.contao.error')->error('Not enough permissions to manage event categories');
            $this->redirect('contao/main.php?act=error');
        }
    }

    /**
     * Auto-generate a category alias if it has not been set yet
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return string
     *
     * @throws Exception
     */
    public function generateAlias($varValue, DataContainer $dc): string
    {
        $autoAlias = false;
        // Generate an alias if there is none
        if ($varValue == '')
        {
            $autoAlias = true;
            $varValue = StringUtil::generateAlias($dc->activeRecord->title);
        }
        $objAlias = $this->Database->prepare("SELECT id FROM tl_mae_event_cat WHERE alias=?")
                                   ->execute($varValue);
        // Check whether the category alias exists
        if ($objAlias->numRows > 1)
        {
            if (!$autoAlias)
            {
                throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
            }
            $varValue .= '-' . $dc->id;
        }
        return $varValue;
    }


    /**
     * delete references in tl_calendar_events and tl_module
     */
    public function onDelete(DataContainer $dc): void
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
