<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_engage
 */

use totara_engage\question\question_resolver as base;

class question_resolver extends base {
    /**
     * @var string|null
     */
    private $component;

    /**
     * @param string $component
     * @return void
     */
    public function set_component(string $component): void {
        if (isset($this->component)) {
            debugging(
                "The component had already been set, please instantiate a new instance of this resolver",
                DEBUG_DEVELOPER
            );

            return;
        }

        $this->component = $component;
    }

    /**
     * @return string
     */
    public function get_component(): string {
        return $this->component;
    }

    /**
     * @param int $userid
     * @return bool
     */
    public function can_create(int $userid): bool {
        return defined('PHPUNIT_TEST') && PHPUNIT_TEST;
    }

    /**
     * @param int $userid
     * @param int $questionid
     *
     * @return bool
     */
    public function can_delete(int $userid, int $questionid): bool {
        return defined('PHPUNIT_TEST') && PHPUNIT_TEST;
    }
}