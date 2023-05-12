<?php
/**
 * This file is part of the App Alumni System.
 * Trait for CSV to Array
 *
 * @author Muhammad Umer Farooq <umer@lablnet.com>
 * @link https://lablnet.com
 *
 * For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 * @since 1.0.0
 */

namespace App\Traits;

trait CSVToArray {

    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;
    
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
    
        return $data;
    }
}