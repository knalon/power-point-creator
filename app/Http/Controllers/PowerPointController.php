<?php

namespace App\Http\Controllers;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Slide\Background\Image;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Font;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PowerPointController extends Controller
{
    public function createSlideTemplate_textOnly($title, $subtitle, $content, $objPHPPowerPoint)
    {
        $imagePath = storage_path('app/public/def_slide_background.png'); // Path to your background image

        // Create a new slide
        $slide = $objPHPPowerPoint->createSlide();

        // Set background image
        $background = new Image();
        $background->setPath($imagePath);
        $slide->setBackground($background);

        // ---- Header Section: Title ----
        $titleShape = $slide->createRichTextShape()
            ->setHeight(100)
            ->setWidth(1220)
            ->setOffsetX(50)
            ->setOffsetY(15);
        $titleShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $titleShape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);

        // Add Title Text
        $textRun = $titleShape->createTextRun($title);
        $font = new Font();
        $font->setName('Montserrat')
            ->setBold(true)
            ->setSize(36)
            ->setColor(new Color('FF8C1A4B'));  // Title color
        $textRun->setFont($font);

        // ---- Subtitle Section (Optional) ----
        if ($subtitle) {
            $subtitleShape = $slide->createRichTextShape()
                ->setHeight(50)
                ->setWidth(1220)
                ->setOffsetX(50)
                ->setOffsetY(115);
            $subtitleShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $subtitleShape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

            $subtitleRun = $subtitleShape->createTextRun($subtitle);
            $subtitleFont = new Font();
            $subtitleFont->setName('Montserrat')
                ->setSize(24)
                ->setBold(true)
                ->setColor(new Color('FF8497B0'));  // Subtitle color
            $subtitleRun->setFont($subtitleFont);
        }

        // ---- Body Section: Dynamic Content ----
        $contentShape = $slide->createRichTextShape()
            ->setHeight(470)
            ->setWidth(1140)
            ->setOffsetX(60)
            ->setOffsetY(170);
        $contentShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
        $contentShape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Parse content by sections, with each section separated by two newlines
        $topicSections = preg_split("/\n\n/", trim($content));

        // Fonts for titles and paragraphs
        // $titleFont = new Font();
        // $titleFont->setName('Arial')->setSize(17)->setBold(false)->setColor(new Color('FF000000'));
        $paragraphFont = new Font();
        $paragraphFont->setName('Arial')->setSize(17)->setColor(new Color('FF000000'));

        // Iterate over sections and apply formatting
        foreach ($topicSections as $section) {
            $parts = preg_split("/\n/", trim($section));

            foreach ($parts as $index => $part) {
                $textRun = $contentShape->createTextRun($part . "\n");
                    $textRun->setFont($paragraphFont);  // Paragraph font for detailed explanations

            }

            // Add spacing between sections
            $contentShape->createTextRun("\n\n");  // Double newline to separate sections
        }

        return $objPHPPowerPoint;
    }



    // Above is Space for Templates.
    // Below is the essential Codes.
    public function createFirstSlide($title, $objPHPPowerPoint){

        $imagePath = storage_path('app/public/background.jpg'); // path to your background image
        $educlaasPath = storage_path('app/public/educlaas.png');
        $lithanPath = storage_path('app/public/lithan.png');
  
        // ---- Slide 1: Full background image with centered title ----
        $slide1 = $objPHPPowerPoint->getActiveSlide();
  
        // Set background image for the first slide
        $background = new Image();
        $background->setPath($imagePath);
        $educlaasLogo = new Image();
        $educlaasLogo->setPath($educlaasPath);
        $lithanLogo = new Image();
        $lithanLogo->setPath($lithanPath);
       
        
        $slide1->setBackground($background);
        // Add centered title
        $titleShape = $slide1->createRichTextShape()
            ->setHeight(165)      // Set height 
            ->setWidth(1280)      // Set width (full)
            ->setOffsetX(0)    // Adjust the horizontal position to left
            ->setOffsetY(60);   // Adjust the vertical position to a bit of top

        // Center the text both horizontally and vertically
        $titleShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $titleShape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        // Add title text
        $textRun = $titleShape->createTextRun($title);

        // Set font style for the title
        $font = new Font();
        $font->setName('Montserrat')  // Set Montserrat font
             ->setBold(true)
             ->setSize(48)
             ->setColor(new Color('FF8C1A4B'));  // Title color: #8c1a4b
        $textRun->setFont($font);

        // ---- Add educlaas logo ----
        $educlaasLogo = $slide1->createDrawingShape();
        $educlaasLogo->setName('EduClaas Logo')
                    ->setPath($educlaasPath)
                    ->setHeight(250)           // Set height of the logo
                    ->setWidth(250)            // Set width of the logo
                    ->setOffsetX(430)           // Set X position (left)
                    ->setOffsetY(330);         // Set Y position (bottom)

        // ---- Add lithan logo ----
        $lithanLogo = $slide1->createDrawingShape();
        $lithanLogo->setName('Lithan Logo')
                ->setPath($lithanPath)
                // ->setHeight(100)           // Set height of the logo
                // ->setWidth(220)            // Set width of the logo
                ->setOffsetX(680)         // Set X position (right side)
                ->setOffsetY(310);         // Set Y position (bottom)
                
        return $objPHPPowerPoint;
    }
    public function createSecondSlide($slidesDataFormatted, $objPHPPowerPoint)
    {
        $imagePath = storage_path('app/public/def_slide_background.png'); // path to your background image

        // Create a new slide
        $slide = $objPHPPowerPoint->createSlide();

        //try to set background image
        $background = new Image();
        $background->setPath($imagePath);
       
        $slide->setBackground($background);  

        // ---- Header Section: Title and Subtitle ----
        // Title
        $titleShape = $slide->createRichTextShape()
            ->setHeight(100)       // Set height for title
            ->setWidth(1220)      // Set width (full)
            ->setOffsetX(50)       // Set X position (left)
            ->setOffsetY(15);     // Set Y position (top)
        // Center the title text
        $titleShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $titleShape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);

        // Add title text
        $textRun = $titleShape->createTextRun("Table Of Content");
        $font = new Font();
        $font->setName('Montserrat')
            ->setBold(true)
            ->setSize(36)
            ->setColor(new Color('FF8C1A4B'));  // Black color for title
        $textRun->setFont($font);

        // ---- Body Section: Content List ----
        $contentShape = $slide->createRichTextShape()
            ->setHeight(500)
            ->setWidth(1200)
            ->setOffsetX(60)
            ->setOffsetY(130);
        $contentShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        
        $lineBreakSingle = "\n";
        $lineBreakDouble = "\n\n";
        $lineBreak='';
            if(count($slidesDataFormatted) <= 8){
                $lineBreak = $lineBreakDouble;
            }else{
                $lineBreak = $lineBreakSingle;
            }
        // Add each title as a bulleted list item
        foreach ($slidesDataFormatted as $slideData) {

            $bulletPointRun = $contentShape->createTextRun($slideData['title'] . $lineBreak);
            $bulletFont = new Font();
            $bulletFont->setName('Arial')
                    ->setSize(20)
                    ->setColor(new Color('FF000000')); // Text color for bullets
            $bulletPointRun->setFont($bulletFont);
        }

        return $objPHPPowerPoint;
    }

    public function createLastSlide($objPHPPowerPoint){

        $imagePath = storage_path('app/public/thanks.png'); // path to your background image
  
        // ---- Slide 1: Full background image with centered title ----
        $lastSlide = $objPHPPowerPoint->createSlide();
  
        // Set background image for the first slide
        $background = new Image();
        $background->setPath($imagePath);
       
        $lastSlide->setBackground($background);         
        return $objPHPPowerPoint;
    }
    public function generatePPT(Request $request){
        $firstTitle = $request->input('firstTitle');
        $slidesDataFormatted = json_decode($request->input('slideData'), true);

        $fileName = Str::slug($firstTitle, '_');  // Create a valid file name based on the title

        // Create a new presentation and set the slide size to 16:9 for all slides
        $objPHPPowerPoint = new PhpPresentation();
        $objPHPPowerPoint->getLayout()->setCX(12192000); // Width: 16 inches
        $objPHPPowerPoint->getLayout()->setCY(6858000);  // Height: 9 inches
        // --- Slide 1 : 
        $objPHPPowerPoint = $this->createFirstSlide($firstTitle, $objPHPPowerPoint);
        // --- Slide 2 & 3 :  (Content Table Slide Creation)
        $batchSize = 15;
        $numBatches = ceil(count($slidesDataFormatted) / $batchSize);

        for ($i = 0; $i < $numBatches; $i++) {
            $startIndex = $i * $batchSize;
            $endIndex = min($startIndex + $batchSize, count($slidesDataFormatted));
            $batch = array_slice($slidesDataFormatted, $startIndex, $endIndex - $startIndex);

            $this->createSecondSlide($batch, $objPHPPowerPoint);
        }
        // --- Slide 4, 5 and so on
        // --- Create Body slides based on GPT response data
        Log::info($slidesDataFormatted);
        foreach ($slidesDataFormatted as $slideData) {
            $slideTitle = $slideData['title'];
            $slideContent = $slideData['content'];
            $this->createSlideTemplate_textOnly($slideTitle, '', $slideContent, $objPHPPowerPoint);
        }

        // ---- Slide Last :
        $objPHPPowerPoint = $this->createLastSlide($objPHPPowerPoint);
        // dd($objPHPPowerPoint);  
        // Save the PowerPoint file with the user-provided title as the filename
        $oWriterPPTX = IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
        $outputFileName = $fileName . '.pptx';  // Use the user-provided title for the file name
        $outputPath = storage_path($outputFileName);
        $oWriterPPTX->save($outputPath);

        // Return the file for download
        return response()->download($outputPath)->deleteFileAfterSend(true);
    }

}
