<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
class WordController extends Controller
    {

    public function generateWordFile(Request $request)
    {
        $firstTitle = $request->input('firstTitle');
        $data = json_decode($request->input('slideData'), true);
        // Log::info($data);
        $fileName = Str::slug($firstTitle, '_') . ".docx";

        // Create a new PHPWord object
        $phpWord = new PhpWord();

        // Define font styles for title and content
        $titleStyle = ['bold' => true, 'size' => 16, 'name' => 'Arial'];
        $contentStyle = ['size' => 12, 'name' => 'Arial'];
        $listStyle = ['name' => 'Arial', 'size' => 12];

        // Add content to the document
        foreach ($data as $entry) {
            $section = $phpWord->addSection();
            $section->addText($entry['title'], $titleStyle);

            // Split content by lines and add them as individual paragraphs or list items
            $lines = explode("\n", $entry['content']);
            foreach ($lines as $line) {
                // $line = str_replace("\'","",trim($line));
                
                log::info($line);
                if (empty($line)) {
                    $section->addTextBreak(); // Adds a blank line for spacing
                } elseif (preg_match('/^\d+\.\s|^\*\*/', $line)) {
                    // Check if it's a list item or subheading (begins with a number or '**')
                    $section->addListItem($line, 0, $listStyle); // Adjust level as needed
                } else {
                    $section->addText($line, $contentStyle);
                }
            }
        }

        // Save the file to the server
        $path = storage_path($fileName);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        // Download the file as a response
        return response()->download($path)->deleteFileAfterSend(true);
    }

}
