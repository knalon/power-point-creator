<!DOCTYPE html>
<html>
<head>
    <title>PowerPoint Creator Input Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e2d7dc;
            margin: 20px;
        }
        h2 {
            color: #8C1A4B;
            text-align: center;
        }
        .form-container {
            background-color: #8C1A4B;
            padding: 20px;
            border-radius: 8px;
            max-width: 800px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }
        label {
            color: #ffffff;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #8C1A4B;
            border-radius: 4px;
        }
        textarea {
            height: 70px;
            resize: none;
        }
        #submit_button {
            height: 40px;
            background-color: #ffffff;
            color: #8C1A4B;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }
        #submit_button:hover {
            background-color: #8C1A4B;
            color: #ffffff;
            border: 1px solid black;
        }
    </style>
    <script>
        function toggleForm() {
            const formType = document.getElementById('formType').value;
            const scenarioField = document.getElementById('scenario');
            const topicField = document.getElementById('topic');
            if (formType === 'general') {
                scenarioField.style.display = 'block';
                topicField.style.display = 'none';
                topicField.required = false;
                scenarioField.required = true;
            } else {
                scenarioField.style.display = 'none';
                topicField.style.display = 'block';
                scenarioField.required = false;
                topicField.required = true;
            }
        }
    </script>
</head>
<body>
    <h2>Structured PowerPoint Topic Input Form</h2>
    <div class="form-container">
        <form action="/generate-preview" method="POST">
            @csrf
            <div class="form-group">
                <label for="formType">Select Presentation Type</label>
                <select id="formType" name="formType" onchange="toggleForm()" required>
                    <option value="general">General</option>
                    <option value="howto">How To</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="title_input">Presentation Title</label>
                <input id="title_input" type="text" name="title" placeholder="Enter PowerPoint file title here..." required />
            </div>
            <div class="form-group">
                <label for="role">AI's Role</label>
                <input type="text" id="role" name="role" placeholder="Example: doctor, assistant, data analyst" required />
            </div>

            <!-- Scenario Description Field -->
            <div class="form-group" id="scenario" style="display: block;">
                <label for="scenario_input">Scenario Description</label>
                <textarea id="scenario_input" name="scenario" placeholder="Describe the scenario where AI takes on this role. E.g., assisting doctors in diagnosing patients."></textarea>
            </div>

            <!-- How-To Topic Field -->
            <div class="form-group" id="topic" style="display: none;">
                <label for="topic_input">How-To Topic</label>
                <input id="topic_input" type="text" name="topic" placeholder="Describe the specific topic here..." />
            </div>

            <div class="form-group">
                <label for="expectations">Expectations</label>
                <textarea id="expectations" name="expectations" placeholder="What should the AI accomplish in this role? E.g., accurately analyze patient data to suggest possible diagnoses." required></textarea>
            </div>

            <div class="form-group">
                <label for="limitations">Limitations</label>
                <textarea id="limitations" name="limitations" placeholder="Any limitations or areas the AI should avoid. E.g., AI should not make final diagnoses without human verification." required></textarea>
            </div>

            <div class="form-group">
                <label for="audience">Target Audience</label>
                <input type="text" id="audience" name="audience" placeholder="Who is this presentation for? E.g., healthcare professionals, general audience." required />
            </div>

            <div class="form-group">
                <label for="noOfSlide">Total Number of Slides Created</label>
                <input type="number" id="noOfSlide" name="noOfSlide" placeholder="How many slides would you like to create? 15-25 is recommended." required />
            </div>
            
            <div class="form-group">
                <input id="submit_button" type="submit" value="Generate Web Preview"/>
            </div>
        </form>
    </div>
</body>
</html>
