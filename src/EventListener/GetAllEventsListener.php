<?php

declare(strict_types=1);

/*
 * Mae Event Categories feed bundle for Contao Open Source CMS
 *
 * Copyright (c) 2024 pdir / digital agentur // pdir GmbH
 *
 * @package    mae_event_categories
 * @link       https://github.com/pdir/mae_event_categories
 * @license    http://www.gnu.org/licences/lgpl-3.0.html LGPL
 * @author     Martin Eberhardt https://www.martin-eberhardt.com
 * @author     Mathias Arzberger <develop@pdir.de>
 * @author     pdir GmbH <https://pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pdir\MaeEventCategoriesBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Database;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\Module;
use Contao\StringUtil;

class GetAllEventsListener
{
    #[AsHook('getAllEvents', priority: 100)]
    public function onGetAllEvents($arrEvents, $arrCalendars, $intStart, $intEnd, Module $objModule): array|null
    {
        // FIXME it's possible to filter a list for categories, it doesn't allow by its configuration
        $result     = [];
        $modCats    = StringUtil::deserialize($objModule->event_categories, true);
        $hasCatCfg  = \is_array($modCats) && \count($modCats) > 0;

        $filterParam_ar = array('category');
        $objFilterMod = Database::getInstance()->prepare("SELECT mae_event_catname FROM tl_module WHERE mae_event_catname != '' AND type='mae_event_filter' AND mae_event_list=?")->execute($objModule->id);
        while($objFilterMod->fetchAssoc()) {
            $filterParam_ar[] = $objFilterMod->mae_event_catname;
        }

        foreach ($filterParam_ar as $paramName) {
            $filterCat  = Input::get($paramName);
            if(!empty($filterCat) && $filterCat != "all") {
                if(!\is_numeric($filterCat)) {
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


        if (\is_array($arrEvents) && \count($arrEvents) > 0 && \count($modCats) > 0) {
            foreach ($arrEvents as $day => $times) {
                foreach ($times as $time => $events) {
                    foreach ($events as $event) {
                        $evtCats = \unserialize($event['categories']);
                        if (!\is_array($evtCats)) {
                            $evtCats = array();
                        }
                        foreach ($modCats as $modCat) {
                            if (\in_array($modCat, $evtCats)) {
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
    }
}
