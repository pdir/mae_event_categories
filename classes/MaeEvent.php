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
        $result     = array();
        $modCats    = unserialize($objModule->event_categories);

        if (!is_array($modCats)) {
            $modCats = array();
        }

        $filterCat  = Input::get('category');
        if(!empty($filterCat) && $filterCat != "all") {
            $modCats = array($filterCat);
        }

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
