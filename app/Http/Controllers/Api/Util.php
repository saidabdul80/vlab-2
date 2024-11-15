<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class Util extends Controller
{
    static public $roleId = [
        'student'=>'3e836670-a9d5-4c78-bfb8-0bdcda27263c',
        'school_admin'=>'61947969-78e6-4619-be35-50541aef8cb3',
        'faculty_admin'=>'9260655c-6933-45a0-8d01-6de3d6a52657',
    ];

    static public function uuid()
    {
        return Str::uuid()->toString();
    }

    static public function csvToArray($filename = '', $delimiter = ',')
    {
        // Check if the file exists and is readable
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new \Exception("Error while reading file");
        }
    
        $header = null;
        $data = [];
    
        // Open the file in read mode
        if (($handle = fopen($filename, 'r')) !== false) {
            // Read each line of the CSV file
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                // Skip rows where all cells are empty
                if (!array_filter($row)) {
                    continue;
                }
    
                if (!$header) {
                    // Set the header if it hasn't been set yet
                    // Trim spaces and remove any empty fields
                    $header = array_filter(array_map('trim', $row)); // Trim and filter out empty values
                    $header = array_values($header); // Reindex the array to remove any gaps
                    //dd($header); // Debugging line to check header content
                } else {
                    // Adjust the row to match the header length
                    $rowCount = count($row);
                    $headerCount = count($header);
    
                    if ($rowCount < $headerCount) {
                        // Pad the row with empty strings if it's shorter than the header
                        $row = array_pad($row, $headerCount, '');
                    } elseif ($rowCount > $headerCount) {
                        // Truncate the row if it's longer than the header
                        $row = array_slice($row, 0, $headerCount);
                    }
    
                    // Combine the header with the adjusted row to form an associative array
                    $data[] = array_combine($header, $row);
                }
            }
            // Close the file handle
            fclose($handle);
        }
    
        return $data;
    }
    


    static public function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

     static public function hasInternetConnection()
    {
        $connected = @fsockopen('www.example.com', 80);

        if($connected){
            fclose($connected);
            return true;
        }

        return false;
    }
}
