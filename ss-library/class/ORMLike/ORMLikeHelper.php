<?php

/**
 * Copyright 2013, Kerem Gunes <http://qeremy.com/>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

/**
 * @class ORMLikeHelper v0.1
 *
 * Help for ORMLike. :)
 */
class ORMLikeHelper
{
    public static function camelize($input) {
        return preg_replace_callback('~_([a-z])~i', function($m) {
            return strtoupper($m[1]);
        }, $input);
    }
}
