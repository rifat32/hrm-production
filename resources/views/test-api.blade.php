<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Call Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 80%;
            margin: 0 auto;
        }
        .result {
            margin-top: 20px;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 15px;
        }
    </style>
       <style>
        .form-group {
            margin: 20px 0;
        }
        textarea {
            width: 100%;
            height: 900px; /* Adjust as needed */
            font-family: monospace; /* For better readability */
            font-size: 16px; /* Increase font size */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>API Call Form </h1>
        <form id="apiForm">
            <div class="form-group">
                <label for="apiUrl">API URL:</label>
                <input type="text" id="apiUrl" name="apiUrl" required value="{{$error_log->api_url}}">
            </div>
            <div class="form-group">
                <label for="requestMethod">Request Method:</label>
                <select id="requestMethod" name="requestMethod" required>
                    <option value="GET" {{ $error_log->request_method == "GET" ? 'selected' : '' }}>GET</option>
                    <option value="POST" {{ $error_log->request_method == "POST" ? 'selected' : '' }}>POST</option>
                    <option value="PUT" {{ $error_log->request_method == "PUT" ? 'selected' : '' }}>PUT</option>
                    <option value="DELETE" {{ $error_log->request_method == "DELETE" ? 'selected' : '' }}>DELETE</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jwtToken">Request time:</label>
              {{$error_log->created_at}}
            </div>

            <div class="form-group">
                <label for="jwtToken">IP address:</label>
              {{$error_log->ip_address}}
            </div>

            <div class="form-group">
                <label for="jwtToken">JWT Token:</label>
                <input type="text" id="jwtToken" name="jwtToken" required value="">
            </div>

            <div class="form-group">
                <label for="jsonBody">JSON Body:</label>
                <textarea id="jsonBody" name="jsonBody" required rows="20" cols="80">{{!empty($error_log->fields) ? json_encode(json_decode($error_log->fields), JSON_PRETTY_PRINT) : '[]'}}</textarea>
                <button type="button" onclick="formatJSON()">Format JSON</button>
            </div>

            <script>
                function formatJSON() {
                    const textarea = document.getElementById('jsonBody');
                    try {
                        const json = JSON.parse(textarea.value);
                        textarea.value = JSON.stringify(json, null, 2); // Indent with 2 spaces
                    } catch (e) {
                        alert('Invalid JSON. Please check your input.');
                    }
                }
            </script>


            <button type="submit">Hit API</button>
        </form>
        <div class="result" id="result"></div>
    </div>

    <script>
        document.getElementById('apiForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const apiUrl = document.getElementById('apiUrl').value;
            const jwtToken = document.getElementById('jwtToken').value;
            const jsonBody = document.getElementById('jsonBody').value;
            const requestMethod = document.getElementById('requestMethod').value;
            const resultDiv = document.getElementById('result');

            // Clear previous result
            resultDiv.textContent = '';

            let options = {
                method: requestMethod,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${jwtToken}`
                }
            };

            if (requestMethod !== 'GET') {
                try {
                    options.body = JSON.stringify(JSON.parse(jsonBody));
                } catch (error) {
                    resultDiv.textContent = 'Invalid JSON body';
                    console.error('Invalid JSON:', error);
                    return;
                }
            }

            fetch(apiUrl, options)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    resultDiv.textContent = `Response: ${JSON.stringify(data, null, 2)}`;
                })
                .catch(error => {
                    resultDiv.textContent = `Error: ${error.message}`;
                    console.error('Error:', error);
                });
        });
    </script>
</body>
</html>
