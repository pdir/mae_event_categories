<?php
ClassLoader::addNamespace('MaeEventCategories');

ClassLoader::addClasses(array
(
    'MaeEventCategories\MaeEvent' => 'system/modules/mae_event_categories/classes/MaeEvent.php',
    'MaeEventCategories\MaeEventBe' => 'system/modules/mae_event_categories/classes/MaeEventBe.php',
    'MaeEventCategories\MaeEventCatModel' => 'system/modules/mae_event_categories/models/MaeEventCatModel.php',
    'MaeEventCategories\ModuleFilter' => 'system/modules/mae_event_categories/modules/ModuleFilter.php'
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
    'mod_mae_event_filter'       	 => 'system/modules/mae_event_categories/templates/modules'
));
