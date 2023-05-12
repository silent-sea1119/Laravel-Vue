<?php
/**
 * This file is part of the App Alumni System.
 *
 * @author Muhammad Umer Farooq <umer@lablnet.com>
 * @link https://lablnet.com
 *
 * For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 * @since 1.0.0
 */

namespace App\Classes;

class Permission {

    public static $alumniPermissions = [
        'admin.jobs.index',
        'admin.jobs.create',
        'admin.jobs.update',
        'admin.jobs.show',
        'admin.jobs.destroy',
    ];

    public static $totalPermissions = [
        'admin.users.index',
        'admin.users.create',
        'admin.users.update',
        'admin.users.show',
        'admin.users.destroy',
        'admin.users.impersonate',
        'admin.users.permissions',

        'admin.faculty.index',
        'admin.faculty.create',
        'admin.faculty.update',
        'admin.faculty.show',
        'admin.faculty.destroy',

        'admin.degree.programs.index',
        'admin.degree.programs.create',
        'admin.degree.programs.update',
        'admin.degree.programs.show',
        'admin.degree.programs.destroy',

        'admin.forums.index',
        'admin.forums.create',
        'admin.forums.update',
        'admin.forums.show',
        'admin.forums.destroy',

        'admin.events.index',
        'admin.events.create',
        'admin.events.update',
        'admin.events.show',
        'admin.events.destroy',

        'admin.events.gallery.index',
        'admin.events.gallery.create',
        'admin.events.gallery.destroy',

        'admin.settings.index', 
    ];

    public static function getAllPermissions ()
    {
        return array_merge(self::$totalPermissions, self::$alumniPermissions);
    }
}
