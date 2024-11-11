<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PreviewController extends Controller
{
    public function generatePreview(Request $request)
    {
        set_time_limit(1200);  // Sets the maximum execution time to 120 seconds   
        $firstTitle = $request->input('title', 'Default Title');
        $slideTitles = null;
        $titlePrompt = $this->generatePrompt($request,
         null, $slideTitles);

        $apiKey = env('OPENAI_API_KEY');
        $titleResponse = Http::timeout(60)->withHeaders([
            'Authorization' => "Bearer $apiKey",
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $titlePrompt],
            ],
            'max_tokens' => 500,
        ]);

        $titleData = json_decode($titleResponse->body(), true);

        if (!isset($titleData['choices'][0]['message']['content'])) {
            throw new Exception("Failed to get slide titles from GPT-3.5-turbo.");
        }

        $titles = explode("\n", $titleData['choices'][0]['message']['content']);
        $slideTitles = array_filter($titles, function($title) { return !empty(trim($title)); });

        // Step 2: Generate Content for Each Slide Title
        $contentSlides = [];
        foreach ($slideTitles as $index => $slideTitle) {
            $contentPrompt = $this->generatePrompt($request, $slideTitle, $slideTitles);

            $contentResponse = Http::timeout(60)->withHeaders([
                'Authorization' => "Bearer $apiKey",
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $contentPrompt],
                ],
                'max_tokens' => 1000,
            ]);

            $contentData = json_decode($contentResponse->body(), true);

            if (isset($contentData['choices'][0]['message']['content'])) {
                $contentSlides[] = [
                    'title' => $slideTitle,
                    'content' => $contentData['choices'][0]['message']['content']
                ];
            } else {
                $contentSlides[] = [
                    'title' => $slideTitle,
                    'content' => 'Content generation failed for this slide.'
                ];
            }
        }

        // Pass titles and content to the view
        return view('preview', [
            'firstTitle' => $firstTitle,
            'slidesDataFormattedFull' => $contentSlides
        ]);
    }

    public function generatePrompt(Request $request, $slideTitle, $slideTitles){
                // Get user inputs
        $formType = $request->input('formType');
        $role = $request->input('role');
        $scenario = $request->input('scenario');
        $expectations = $request->input('expectations');
        $limitations = $request->input('limitations');
        $audience = $request->input('audience');
        $topic = $request->input('topic');
        $slideTitles = json_encode($slideTitles)        ;

        if ($formType === 'general' && !($slideTitle)) {
            return "Create titles for a PowerPoint presentation on the topic 
            of AI's role as a \"$role\".
            Context:
            - **Scenario**: $scenario
            - **Expectations**: $expectations
            - **Limitations**: $limitations
            - **Audience**: $audience
            Generate a list of at least".$request->input('noOfSlide','15')."  slide titles that logically outline this 
            presentation, covering each major aspect of the topic without including 
            introductory or concluding slides.";
        } else if ($formType === 'howto' && !($slideTitle)) {
            return "Generate a list of at least".$request->input('noOfSlide','15')." slide titles that logically 
            outline this word document, with each title focusing on a distinct subtopic 
            or aspect of the topic : \"$topic\". Each title should build on the previous slide's 
            content without overlap, ensuring a smooth, progressive flow.";
        }else if($formType === 'general' && $slideTitle){
            return "For a PowerPoint slide titled \"$slideTitle\" on the topic of AI as \"$role\":
            - Provide two main points, each with a detailed paragraph explaining its significance in this context.
            - Use the provided details:
            - **Scenario**: $scenario
            - **Expectations**: $expectations
            - **Limitations**: $limitations
            - **Audience**: $audience";
        }else if($formType === 'howto' && $slideTitle){
            return "This prompt is part of the successive prompts to create a power point file.
            For each title, a prompt is being used to generate content.
            The purpose of the code is to make openAI create the project 
            and make a report of the whole process so that I can follow the steps.
            This is the list of the pages \"$slideTitles\". The current page is titled \"$slideTitle\".
            The current page should build on the previous page's content 
            without overlap, ensuring a smooth, progressive flow.
            Throughout the whole powerpoint file,
            - Provide a detailed, step-by-step guide that I can follow 
                and demonstrate each part of the process. 
            - include specific steps, technical requirements, and tips for each stage, 
                as well as any preliminary setup needed. 
            - include specific code snippets as well.
            - should be easy for beginners to understand.
            - Do not add page numbers.
            - Do not generat any * or ** or *** or **** or # or ## or ### or #### in the response.
            - Do not use any * or ** or *** or **** or # or ## or ### or #### or _ in the response.
            - Use the provided details:
            - **Scenario**: $topic
            - **Expectations**: $expectations
            - **Limitations**: $limitations
            - **Audience**: $audience
            - **Front End Technology**: Microsoft Copilot Studio (GUI - Web version)
            - **Back End Technology**: Microsoft Copilot Studio (GUI - Web Version)";
    
        }
    }
}
