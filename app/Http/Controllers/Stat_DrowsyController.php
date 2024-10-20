<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Stat_DrowsyController extends Controller
{
    public function index()
    {
        $lines = file('D:\DSC_Dataset\txtScuffed\Folder Website\public\drowsy.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $hours = [];

        foreach ($lines as $x) {
            $parts = explode(":", $x, 3);

                $hours[] = $parts[0] == 0? 24 : ltrim($parts[0], "0");    
           
          }
        $count = count($lines);
      
        $violation_count = array_values(array_count_values($hours));
        $hours = array_values(array_unique($hours));

        $hours_total =  max($hours) - min($hours) + 1;

        $new = [];
        for ($i = 0; $i < count($hours); $i++) {
            $new[$hours[$i]] = $violation_count[$i];
        }

        $jam_kerja = [8 => 0, 9 => 0, 10 => 0,11 => 0,12 => 0,
        13 => 0,14 => 0,15 => 0,16 => 0,17 => 0];
        
        foreach($new as $key => $value) {
            if(isset($jam_kerja[$key])){
                $jam_kerja[$key] = $value;
            }
        }

        $labels = array_keys($jam_kerja);
        
        $data = array_values($jam_kerja);
       




         // Pie Chart
        $labels_pie = ['Compliance', 'Violation'];

        $data_pie = [63, 37];

        // vio chart
        $labels_vio = ['Welder', 'Driver', 'Div 3', 'Div 4'];

        $data_vio = [3, 10, 8, 5]; 

        // Table
         $tableData = [
            ['Worker 1', 'Gloves', '12:00'],
            ['Worker 2', 'Helmet', '12:45'],
            ['Worker 3', 'Mask', '15:00'],
            ['Worker 4', 'Gloves', '16:00'],
        ];

        //Activity Timeline
        $labels_timeline = [];
        $data_timeline = [];

        $time = strtotime('16:49:00');
        for ($i = 0; $i < 10; $i++) {
            $labels_timeline[] = date('H:i:s', $time + ($i * 60));
            $data_timeline[] = rand(0, 5);
        }

        return view('statistics-drowsy', compact('labels', 'data', 
        'labels_pie', 'data_pie', 'labels_vio', 'data_vio', 'labels_timeline', 'data_timeline', 'lines', 
        'count', 'hours', 'hours_total', 'new', 'jam_kerja', 'violation_count'));
    }

    public function worker()
    {
        return view('statistics-drowsy-worker');
    }
}
