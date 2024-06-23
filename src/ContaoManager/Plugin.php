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

namespace Pdir\MaeEventCategoriesBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\CalendarBundle\ContaoCalendarBundle;
use Pdir\MaeEventCategoriesBundle\PdirMaeEventCategoriesBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(PdirMaeEventCategoriesBundle::class)
                ->setLoadAfter(
                    [
                        ContaoCoreBundle::class,
                        ContaoCalendarBundle::class
                    ]
                ) // @phpstan-ignore-line
                ->setReplace(['mae_event_categories']),
        ];
    }
}
