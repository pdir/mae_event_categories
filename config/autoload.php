<?php
ClassLoader::addNamespace('MaeEventCategories');

ClassLoader::addClasses(array
(
    'MaeEventCategories\MaeEvent' => 'system/modules/mae_event_categories/classes/MaeEvent.php',

    // Models
	'MaeEventCategories\MaeEventCatModel' => 'system/modules/mae_event_categories/model/MaeEventCatModel.php'
));
