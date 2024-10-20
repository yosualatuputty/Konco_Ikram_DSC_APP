<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DetectController extends Controller
{
    public function detectVideo(Request $request)
    {
        $output = shell_exec('echo "Hello from shell_exec"');

        $videoData = $request->input('video');

        $image = str_replace('data:image/png;base64,', '', $videoData);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        $tempFilePath = storage_path('app/temp_image.png');
        file_put_contents($tempFilePath, $imageData);

        $output = shell_exec('python "' . base_path('scripts/detect.py') . '" "' . $tempFilePath . '"');
        Log::info($output);

        $output2 = shell_exec('python "' . base_path('scripts/result.py'));

        return response()->json(['result' => $output2]);
    }
}
