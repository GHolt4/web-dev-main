<x-layout>
    <x-slot:heading>
        Home Test Page
    </x-slot:heading>
    <style>
        .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        }

        .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
        }

        .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        }

        .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        }

        input:checked + .slider {
        background-color: #2196F3;
        }

        input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        }


        body {
        font-family: sans-serif;
        }
        label {
        display: block;
        margin-bottom: 5px;
        }
        input[type="number"] {
        width: 150px;
        padding: 5px;
        margin-bottom: 10px;
        box-sizing: border-box;
        }
        #unit-toggle {
            display: flex;
            gap: 10px;
        }
    </style>
<head>
<body>
        <h1>Test</h1>
            </style>
            </head>
            <body>                
            <form id="bikeForm" onsubmit="getBikes().catch(error => console.error('Error:', error));">
                <label class="switch">
                    <input type="checkbox" id="unit-toggle">
                    <span class="slider"></span>
                </label>
                <span id="unit-label">Centimeters (cm)</span>
              <label for="height">Height:</label>
              <div id="height-input">
                  <input type="number" id="height-cm" name="height-cm">
                  <div id = "height-ftin" style="display: none;">
                      <input type="number" id="height-ft" name="height-ft" placeholder="Feet">
                      <input type="number" id="height-in" name="height-in" placeholder="Inches">
                  </div>
              </div>
              <label for="inseam">Inseam:</label>
              <div id="inseam-input">
                  <input type="number" id="inseam-cm" name="inseam-cm">
                  <div id = "inseam-ftin" style="display: none;">
                      <input type="number" id="inseam-ft" name="inseam-ft" placeholder="Feet">
                      <input type="number" id="inseam-in" name="inseam-in" placeholder="Inches">
                  </div>
              </div>
              <label for="leg-length">Leg Length:</label>
              <div id="leg-length-input">
                  <input type="number" id="leg-length-cm" name="leg-length-cm">
                  <div id = "leg-length-ftin" style="display: none;">
                      <input type="number" id="leg-length-ft" name="leg-length-ft" placeholder="Feet">
                      <input type="number" id="leg-length-in" name="leg-length-in" placeholder="Inches">
                  </div>
              </div>
              <label for="arm-length">Arm Length:</label>
              <div id="arm-length-input">
                  <input type="number" id="arm-length-cm" name="arm-length-cm">
                  <div id = "arm-length-ftin" style="display: none;">
                      <input type="number" id="arm-length-ft" name="arm-length-ft" placeholder="Inches">
                      <input type="number" id="arm-length-in" name="arm-length-in" placeholder="Inches">
                  </div>
              </div>
            
              <button type="submit">Submit</button>
            </form>
            
            <script>

                async function getBikes() {
                const corsProxy = 'https://cors-anywhere.herokuapp.com/';
                const id = '';
                const pageUrl = new URL(corsProxy + 'https://api.99spokes.com/v1/bikes');
                pageUrl.searchParams.set('include', 'sizes', 'subcategory');
                pageUrl.searchParams.set('subcategory', 'enduro');
                pageUrl.searchParams.set('year', 2024);
                
                const TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2NvdW50TmFtZSI6Imdlb3JnZWhvbHQiLCJ2ZXJzaW9uIjoxLCJpYXQiOjE3MzM3ODkzMzJ9.xWEMcMU4QUoLm8Xvy6lkznGPeV3Fe_CTlSrk_4IDUOo'; // Replace with your actual token
                
                const responses = await Promise.all([     
                    fetch(pageUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${TOKEN}`
                        }
                    })
                ]);
                
      
                const jsonData = await responses[0].json(); // Since there's only one request, use responses[0]

console.log("Full API Response:", jsonData); // Debugging: Check structure

// Ensure 'items' exists and is an array before using forEach
if (jsonData.items && Array.isArray(jsonData.items)) {
    jsonData.items.forEach((item, index) => {
        console.log(`${index + 1}. ${item.maker} - ${item.model}`);
    });
} else {
    console.error("Error: Unexpected response format", jsonData);
}

   
      
            // responses.items.forEach((item, index) => { 
            //     console.log(`${index + 1}. ${item.maker} - ${item.model}`);
            // });
        

                // Get user input data
                let ridingStyle = 'aggressive'; 
                var inseam = parseFloat(document.getElementById('inseam-cm').value); // currently in cm
                let height = document.getElementById('height-cm').value; // currently in cm
                console.log(inseam);

                for (let i = 0; i < responses.length; i++) {
                    //console.log(`Test ${i + 1} status:`, responses[i].status); //testing for statuses
                    try {
                        const text = await responses[i].text();
                        var txt = JSON.parse(text); 
                       //console.log(`response:`, txt);  //error state returns status 
                      getBikesFromAPI(txt, inseam);

                       
                    } catch (e) {
                        console.log(`Test ${i + 1} error:`, e);//error state returns status +error
                    }
                }
                }
                
                function bikeSizeCalculator(height, inseam, ridingStyle, sizes) {
                //console.log(sizes); // use this variable later to create better sizing logic. Sizes returned not always helpful, needs some logic if rider height exists use that else use sizes name 
                
                let frameSize;
                
                
                //Sizes should be imported from data returned above.
                let small = 71.12;
                let medium = 81.28;
                let large = 83.28; 
                let xlarge = 86.36;
                
                if (ridingStyle === "chilled") {
                 modifier = 0; //Modifier to change size chosen based on riding style, more chilled riders may want to size up slightly for stability 
                } else if (ridingStyle === "aggressive"){
                 modifier = 2; //Modifier to change size chosen based on riding style, more aggressive riders may want to size down slightly for agility  
                }
                // Implement Mountain Bike Sizing Logic
                if((inseam + modifier) >= small  && (inseam + modifier)<= medium){
                    frameSize = "Small";
                } else if ((inseam + modifier) >= medium  && (inseam + modifier) <= large){
                    frameSize = "Medium";
                } else if ((inseam + modifier) >= large && (inseam + modifier) <= xlarge){
                    frameSize = "Large";
                }
                else{
                    alert("No frame size found");
                }
                // Add Height-Based Refinement and Standover Check Here
                console.log(inseam + modifier);
                return `Recommended Frame Size: ${frameSize} (This is an estimate. Professional fitting is recommended)`;
                }

                function getBikesFromAPI(size, inseam){
                    inseam = parseFloat(inseam);
                    console.log(`response:`, size);  //error state returns status 
                    //console.log(bikeSizeCalculator(177.8, inseam, "aggressive", size.items[0].sizes));

              
                }
                </script>

            <script>
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

            //prevents form from refreshing page. 
            var form = document.getElementById("bikeForm");
            function handleForm(event) { event.preventDefault(); } 
            form.addEventListener('submit', handleForm);
        </script>
      
      </body>
      </html>
</x-layout>