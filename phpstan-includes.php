<?php

// Get the full path to the custom field behaviors
$getCustomFields = fn () => glob(__DIR__ . '/storage/runtime/compiled_classes/CustomFieldBehavior*.php');

// Check if there are no custom field behaviors yet. If there aren't
// we can automatically generate them by calling Craft's install/check
// command. This will bootstrap Craft and create the behaviors.
if (empty($getCustomFields())) {
    `php craft install/check`;
}

// Return the custom field behaviors to PHPStan as a file to be scanned
// during the analysis. This will allow PHPStan to understand the Craft's
// custom fields on entries, such as $entry->customFieldHandle
return [
    'parameters' => [
        'scanFiles' => $getCustomFields()
    ]
];
