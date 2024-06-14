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

namespace Pdir\MaeEventCategoriesBundle\Controller\FrontendModule;

use Contao\BackendTemplate;
use Contao\Input;
use Contao\Module;
use Contao\StringUtil;
use Contao\System;

class ModuleFilter extends Module
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_mae_event_filter';

    public function generate(): string
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();
        $scopeMatcher = System::getContainer()->get('contao.routing.scope_matcher');

        if ($request && $scopeMatcher->isBackendRequest($request)) {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### MODULE EVENT FILTER ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    /**
     * Compile the current element
     */
    protected function compile(): void
    {
        $allowAllCats   = false;
        $paramName      = empty($this->mae_event_catname) ? "category" : $this->mae_event_catname;
        $selectedCat    = Input::get($paramName);
        $onlyFutureCats = $this->mae_only_future_cat == '1';
        $futureCats     = array();

        $this->Template->selectedCategory = empty($selectedCat) ? "all" : $selectedCat;
        $this->Template->showAllHref = $this->addToUrl($paramName . '=all');

        $filterCats = empty($this->event_categories) ? array() : \unserialize($this->event_categories);
        if(\count($filterCats) == 0) {
            if($this->mae_event_list > 0) {
                // take category list from event list, if configured
                $objListCats = $this->Database->prepare("SELECT event_categories FROM tl_module WHERE id=?")->execute($this->mae_event_list);
                if($objListCats->numRows == 1) {
                    $listCats = $objListCats->event_categories;
                    if(!empty($listCats)) {
                        $filterCats = \unserialize($listCats);
                    }
                }
            }
            if(\count($filterCats) == 0) {
                // take all categories, because there are no categories defined neither in filter, nor in event list
                $allowAllCats = true;
            }
        }
        if($onlyFutureCats) {
            $futureCats = $this->getFutureCategories();
        }

        $item_ar = array();
        if(\count($filterCats) > 0 || $allowAllCats) {
            if($allowAllCats) {
                $sqlSort = "SELECT * FROM tl_mae_event_cat ORDER BY title";
                $objCats = $this->Database->execute($sqlSort);
                while ($item = $objCats->fetchAssoc()) {
                    $item_ar[] = $item;
                }
            }
            else {
                foreach ($filterCats as $catId) {
                    $objItem = $this->Database->prepare("SELECT * FROM tl_mae_event_cat WHERE id=?")->execute($catId);
                    if($objItem->numRows == 1) {
                        $item_ar[] = $objItem->fetchAssoc();
                    }
                }
            }
            foreach ($item_ar as $item) {
                if($onlyFutureCats && !\in_array($item['id'], $futureCats)) {
                    continue;
                }
                if($selectedCat == $item['id'] || $selectedCat == $item['alias']) {
                    $item['cssClass'] = $item['cssClass'] . " active";
                }
                if(!empty($item['cssClass'])) {
                    $item['cssClass'] = " " . \trim($item['cssClass']);
                }
                if(empty($item['cssId'])) {
                    $item['cssId'] = "mae_cat_" . $item['id'];
                }
                $item['href'] = $this->addToUrl($paramName . '=' . (empty($item['alias']) ? $item['id'] : $item['alias']));
                $items[] = $item;
            }
        } // if you have categories
        $this->Template->items = $items;
    }

    /**
     * get all categories, assigned to published events in the future
     * @return array an array of category ids
     */
    protected function getFutureCategories(): array
    {
        $result = [];
        $now = \time();
        $sqlCat = "SELECT categories FROM tl_calendar_events WHERE published='1' AND (startTime >= " . $now . " OR endTime >= " . $now . ")";
        $objCat = $this->Database->execute($sqlCat);
        while ($cat = $objCat->fetchAssoc()) {
            $eventCats = $cat['categories'];
            if(!empty($eventCats)) {
                $eventCats = StringUtil::deserialize($eventCats, true);
                foreach ($eventCats as $eventCatId) {
                    if(!\in_array($eventCatId, $result)) {
                        $result[] = (int)$eventCatId;
                    }
                }
            }
        }
        return $result;
    }
}
