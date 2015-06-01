<?php
ClassLoader::addNamespace(array('MaeEventCategories'));

ClassLoader::addClasses(array
(
    'MaeEventCategories\MaeEvent' => 'system/modules/mae_event_categories/classes/MaeEvent.php',

    // Models
	'MaeEventCategories\MaeEventCatModel' => 'system/modules/mae_event_categories/models/MaeEventCatModel.php'
));
