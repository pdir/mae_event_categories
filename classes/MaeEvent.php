<?php

/**
 * Contao Open Source CMS
 *
 *
 * @package   MaeEventCategories
 * @author    Martin Eberhardt
 * @license   GNU/LGPL
 * @copyright Martin Eberhardt Webentwicklung & Photographie 2015
 */


/**
 * Namespace
 */
namespace MaeEventCategories;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\System;


/**
 * Class MaeEvent
 * implements the hook getAllEvents()
 *
 * @copyright  Martin Eberhardt Webentwicklung & Photographie 2015
 * @author     Martin Eberhardt
 * @package    MaeEventCategories
 */
class MaeEvent extends \Frontend
{

    public function getAllEvents($arrEvents, $arrCalendars, $intStart, $intEnd, \Contao\Module $objModule)
    {
        // FIXME it's possible to filter a list for categories, it doesn't allow by its configuration
        $result     = array();
        $modCats    = deserialize($objModule->event_categories, true);
        $hasCatCfg  = is_array($modCats) && count($modCats) > 0;

        $filterParam_ar = array('category');
        $objFilterMod = Database::getInstance()->prepare("SELECT mae_event_catname FROM tl_module WHERE mae_event_catname != '' AND type='mae_event_filter' AND mae_event_list=?")->execute($objModule->id);
        while($objFilterMod->fetchAssoc()) {
            $filterParam_ar[] = $objFilterMod->mae_event_catname;
        }

        foreach ($filterParam_ar as $paramName) {
            $filterCat  = Input::get($paramName);
            if(!empty($filterCat) && $filterCat != "all") {
                if(!is_numeric($filterCat)) {
                    $objCat = Database::getInstance()->prepare("SELECT id FROM tl_mae_event_cat WHERE alias=?")->execute($filterCat);
                    if($objCat->numRows == 1) {
                        $filterCat = $objCat->id;
                    }
                }
                if($hasCatCfg) {
                    $modCats = array($filterCat);
                    $hasCatCfg = false;
                }
                else {
                    $modCats[] = $filterCat;
                }
            } // have filter value
        } // each possible category url parameter


        if (is_array($arrEvents) && count($arrEvents) > 0 && count($modCats) > 0) {
            foreach ($arrEvents as $day => $times) {
                foreach ($times as $time => $events) {
                    foreach ($events as $event) {
                        $evtCats = unserialize($event['categories']);
                        if (!is_array($evtCats)) {
                            $evtCats = array();
                        }
                        foreach ($modCats as $modCat) {
                            if (in_array($modCat, $evtCats)) {
                                $result[$day][$time][] = $event;
                                break;
                            }
                        } // compare categories module <=> event
                    } // event
                } // times
            } // days
        } // if categories specified in module
        else {
            $result = $arrEvents;
        } // if no category filter set
        return $result;
    } // getAllEvents()
}
