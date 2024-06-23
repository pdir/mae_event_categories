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

namespace Pdir\MaeEventCategoriesBundle\EventListener\DataContainer;

use Contao\BackendUser;
use Contao\Connection;
use Contao\StringUtil;

#[AsCallback(table: 'tl_calendar_events', target: 'config.onsubmit')]
class CalendarEventsListener
{
    private $User;

    public function __construct(Connection $db)
    {
        $this->db = $db;
        $this->User = BackendUser::getInstance();
    }
    public function __invoke($table, $id): void
    {
        $catDefault = $this->User->maeEventCatDefault;
        if($table == "tl_calendar_events" && \is_array($catDefault) && \count($catDefault) > 0) {
            $this->db->prepare("UPDATE tl_calendar_events SET categories=? WHERE id=?")->execute(StringUtil::serialize($catDefault), $id);
        }
    }
}
