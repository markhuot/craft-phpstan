<?php

namespace modules;

use Craft;
use craft\elements\Entry;

class Module extends \yii\base\Module
{
    public function init(): void
    {
        parent::init();

        $entry = new Entry();

        if (function_exists('\PHPStan\dumpType')) {

            // Correctly dumps string|null because the CustomFieldBehavior properly types
            // the sampleTextField property as string|null for text fields.
            \PHPStan\dumpType($entry->sampleTextField);

            // Incorrectly returns a type of CustomFieldBehavior because the method is typed
            // with a return type of `static` on the behavior and PHPStan doesn't late static
            // bind the behavior so we don't get the calling class, we get the defined class.
            \PHPStan\dumpType(Entry::find()->sampleTextField('test'));

        }
    }
}
