<x-layout>
    <x-slot:heading>
        Home Test Page
    </x-slot:heading>
<title>Bike Size Calculator</title>
<style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
 
        #bikeForm {
            margin-bottom: 20px;
        }
 
        #loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
 
        .bike-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
 
        .bike-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
        }
 
        .bike-header h2 {
            margin: 0;
            font-size: 1.2rem;
            color: #333;
        }
 
        .bike-content {
            padding: 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
 
        .bike-sizes h3, .bike-recommendation h3 {
            margin-top: 0;
            font-size: 1rem;
            color: #666;
        }
 
        .size-recommendation {
            font-size: 1.2rem;
            color: #2563eb;
            font-weight: bold;
        }
 
        .fitting-note {
            font-size: 0.875rem;
            color: #666;
            margin-top: 5px;
        }
 
        .error-message {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
 
        /* Form styling */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            margin-right: 10px;
        }
 
        input {
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
 
        button {
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
 
        button:hover {
            background-color: #1d4ed8;
        }
 
        @media (max-width: 768px) {
            .bike-content {
                grid-template-columns: 1fr;
            }
        }
</style>

<script>
    async function getBikes() {
    const resultsDiv = document.getElementById('results');
    const loadingDiv = document.getElementById('loading');
    // Show loading state
    loadingDiv.style.display = 'block';
    resultsDiv.innerHTML = '';
 
    const corsProxy = 'https://webgh.selbyweb.co.uk/proxy.php?url=';
    const pageUrl = new URL(corsProxy + 'https://api.99spokes.com/v1/bikes');
    pageUrl.searchParams.set('include', 'sizes', 'subcategory');
    pageUrl.searchParams.set('subcategory', 'enduro');
    pageUrl.searchParams.set('year', 2024);
    const TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2NvdW50TmFtZSI6Imdlb3JnZWhvbHQiLCJ2ZXJzaW9uIjoxLCJpYXQiOjE3MzM3ODkzMzJ9.xWEMcMU4QUoLm8Xvy6lkznGPeV3Fe_CTlSrk_4IDUOo';
 
    try {
        const response = await fetch(pageUrl, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${TOKEN}`
            }
        });
 
        const jsonData = await response.json();
        // Get user input data
        const ridingStyle = 'aggressive';
        const inseam = parseFloat(document.getElementById('inseam-cm').value);
        const height = parseFloat(document.getElementById('height-cm').value);
 
        if (isNaN(inseam) || isNaN(height)) {
            throw new Error('Please enter valid height and inseam measurements');
        }
 
        // Process bikes and create display elements
        if (jsonData.items && Array.isArray(jsonData.items)) {
            jsonData.items.forEach(bike => {
                const recommendation = bikeSizeCalculator(height, inseam, ridingStyle, bike.sizes);
                const bikeCard = createBikeCard(bike, recommendation);
                resultsDiv.appendChild(bikeCard);
            });
        }
    } catch (error) {
        showError(error.message);
    } finally {
        loadingDiv.style.display = 'none';
    }
}
 
function bikeSizeCalculator(height, inseam, ridingStyle, sizes) {
    let frameSize;
    let modifier = 0;
    const small = 71.12;
    const medium = 81.28;
    const large = 83.28;
    const xlarge = 86.36;
    if (ridingStyle === "chilled") {
        modifier = 0;
    } else if (ridingStyle === "aggressive") {
        modifier = 2;
    }
 
    const adjustedInseam = inseam + modifier;
    if (adjustedInseam >= small && adjustedInseam <= medium) {
        frameSize = "Small";
    } else if (adjustedInseam >= medium && adjustedInseam <= large) {
        frameSize = "Medium";
    } else if (adjustedInseam >= large && adjustedInseam <= xlarge) {
        frameSize = "Large";
    } else {
        frameSize = "No suitable frame size found";
    }
 
    return frameSize;
}
 
function createBikeCard(bike, recommendation) {
    const card = document.createElement('div');
    card.className = 'bike-card';
    card.innerHTML = `
<div class="bike-header">
<h2>${bike.maker} ${bike.model} (${bike.year})</h2>
</div>
<div class="bike-content">
<div class="bike-sizes">
<h3>Available Sizes:</h3>
<p>${bike.sizes ? bike.sizes.join(', ') : 'Sizes not available'}</p>
</div>
<div class="bike-recommendation">
<h3>Recommended Size:</h3>
<p class="size-recommendation">${recommendation}</p>
<p class="fitting-note">This is an estimate. Professional fitting is recommended.</p>
</div>
</div>
    `;
    return card;
}
 
function showError(message) {
    const resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = `
<div class="error-message">
            ${message}
</div>
    `;
}
</script>
</head>

<form id="bikeForm">
<label class="switch">
<input type="checkbox" id="unit-toggle">
<span class="slider"></span>
</label>
<span id="unit-label">Centimeters (cm)</span>
<div>
<label for="height">Height:</label>
<div id="height-input">
<input type="number" id="height-cm" name="height-cm">
<div id="height-ftin" style="display: none;">
<input type="number" id="height-ft" name="height-ft" placeholder="Feet">
<input type="number" id="height-in" name="height-in" placeholder="Inches">
</div>
</div>
</div>
 
        <div>
<label for="inseam">Inseam:</label>
<div id="inseam-input">
<input type="number" id="inseam-cm" name="inseam-cm">
<div id="inseam-ftin" style="display: none;">
<input type="number" id="inseam-ft" name="inseam-ft" placeholder="Feet">
<input type="number" id="inseam-in" name="inseam-in" placeholder="Inches">
</div>
</div>
</div>
 
        <button type="submit">Find Bikes</button>
</form>
 
    <div id="loading">Loading...</div>
<div id="results"></div>
 
    <script>
        // Your existing unit toggle code here
        const unitToggle = document.getElementById('unit-toggle');
        const cmInputs = document.querySelectorAll('[id$="-cm"]');
        const ftInInputs = document.querySelectorAll('[id$="-ftin"]');
        const unitLabel = document.getElementById('unit-label');
        unitToggle.addEventListener('change', () => {
            if (unitToggle.checked) {
                cmInputs.forEach(input => input.style.display = 'none');
                ftInInputs.forEach(input => input.style.display = 'block');
                unitLabel.textContent = "Feet/Inches (ft/in)";
            } else {
                cmInputs.forEach(input => input.style.display = 'block');
                ftInInputs.forEach(input => input.style.display = 'none');
                unitLabel.textContent = "Centimeters (cm)";
            }
        });
 
        // Prevent form from refreshing page
        const form = document.getElementById("bikeForm");
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            getBikes();
        });
</script>
</x-layout>