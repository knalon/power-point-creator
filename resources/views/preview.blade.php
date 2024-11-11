<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerPoint Preview Carousel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .carousel-container {
            max-width: 80vw;
            margin: 60px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* overflow: hidden; */
            position: relative;
        }
        h2 {
            text-align: center;
            font-size: 2em;
            color: #8C1A4B;
            padding: 20px;
            margin: 0;
            background-color: #f3e4ea;
        }
        .carousel-slide {
            position: relative;
            display: none;
            padding: 20px;
            text-align: left;
            z-index: 10;
        }
        .carousel-slide.active {
            display: block;
        }
        .slide-number {
            font-size: 1.5em;
            font-weight: bold;
            color: #8C1A4B;
        }
        h3 {
            font-size: 1.8em;
            color: #8C1A4B;
            margin: 10px 0;
        }
        p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
            text-align: justify;
            white-space: pre-line; /* handles \n as line breaks */
            margin: 10px 0;
        }
        .content-container {
            height: 300px; /* Set a fixed height for content container */
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            padding-bottom: 20px;
        }
        button {
            background-color: #8C1A4B;
            color: white;
            font-size: 1em;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #6E1240;
        }
        .carousel-controls {
            display: flex;
            justify-content: space-between;
            position: absolute;
            top: 50%;
            width: calc(120%); /* Expand width to accommodate outside arrows */
            left: -10%; /* Center arrows outside container */
            transform: translateY(-50%);
            z-index: 0 ;
        }
        .carousel-controls button {
            background-color: #f3e4ea;
            color: #8C1A4B;
            border: none;
            font-size: 1.5em;
            padding: 20px;
            cursor: pointer;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .carousel-controls button:hover {
            background-color: #E5CBD8;
        }
        .z-10{
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="carousel-container">
        <h2>{{ $firstTitle }}</h2>

        <!-- Carousel Slides -->
        <div class="z-10">
            @foreach ($slidesDataFormattedFull as $index => $slide)
            <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" id="slide-{{ $index }}">
                <div class="slide-number">Slide {{ $index + 1 }} / {{ count($slidesDataFormattedFull)}}</div>
                <h3>{{ $slide['title'] }}</h3>
                <div class="content-container">
                    <p>{{ $slide['content'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Carousel Navigation Controls -->
        <div class="carousel-controls">
            <button onclick="prevSlide()">❮</button>
            <button onclick="nextSlide()">❯</button>
        </div>
        
        <!-- Confirm Button -->
        <div class="button-container">
            {{-- <form action="/save-ppt" method="POST">
                @csrf
                <input type="hidden" name="firstTitle" value="{{ $firstTitle }}" />
                <input type="hidden" name="slideData" value="{{ json_encode($slidesDataFormattedFull) }}">
                <button type="submit">Save as PowerPoint</button>
            </form> --}}
            <form method="POST">
                @csrf
                <input type="hidden" name="firstTitle" value="{{ $firstTitle }}" />
                <input type="hidden" name="slideData" value="{{ json_encode($slidesDataFormattedFull) }}">
                <button type="submit" formaction="/save-ppt">Save as PowerPoint</button>
                <button type="submit" formaction="/save-word">Save as Word</button>
            </form>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

    </script>
</body>
</html>
