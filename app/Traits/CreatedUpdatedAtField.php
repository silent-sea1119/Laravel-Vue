<?php
/**
 * This file is part of the App Alumni System.
 * Trait for updated at and created at field
 *
 * @author Muhammad Umer Farooq <umer@lablnet.com>
 * @link https://lablnet.com
 *
 * For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 * @since 1.0.0
 */

namespace App\Traits;

trait CreatedUpdatedAtField {
    /**
     * Get the created_at field in human readable format.
     */
    public function getCreatedAtAttribute($value) {
        if ($value) {
            return \Carbon\Carbon::parse($value)->diffForHumans();
        }
    }
    
    /*
    * Get the updated_at field in human readable format.
    */
    public function getUpdatedAtAttribute($value) {
        if ($value) {
            return \Carbon\Carbon::parse($value)->diffForHumans();
        }
    }
}
