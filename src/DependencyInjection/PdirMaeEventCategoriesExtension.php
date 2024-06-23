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

namespace Pdir\MaeEventCategoriesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PdirMaeEventCategoriesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');
    }
}
