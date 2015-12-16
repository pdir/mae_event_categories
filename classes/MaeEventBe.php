<?php
/**
 * Created by PhpStorm.
 * User: martineberhardt
 * Date: 16.12.15
 * Time: 14:41
 */

namespace MaeEventCategories;


use Contao\Backend;
use Contao\Database;

class MaeEventBe extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function setDefaultCategories($table, $id)
    {
        $catDefault = $this->User->maeEventCatDefault;
        if($table == "tl_calendar_events" && is_array($catDefault) && count($catDefault) > 0) {
            Database::getInstance()->prepare("UPDATE tl_calendar_events SET categories=? WHERE id=?")->execute(serialize($catDefault), $id);
        }
    }
}