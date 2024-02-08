<?php

namespace yii {
    class BaseYii
    {
        /**
         * @var \craft\web\Application|\craft\console\Application|null
         */
        public static $app;

        /**
         * @template T
         * @param class-string<T>|array{class: class-string<T>}|callable(): T $type
         * @param array<mixed> $params
         * @return T
         */
        public static function createObject($type, array $params = []);
    }
}
