<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Aynchronous Form</title>
    </head>
    <style>
        form{
            width: 50vw;
            display: flex; 
            flex-direction: column;
        }
        form label{
            display:grid;

        }

        #results{
            display: none;
        }
        #errors{

            display: none;
        }
        .error{
            border: 2px solid red;
        }
        #spinners {
            display: none;

        }
        #spinners img{
            width: 1rem;
            animation:  anime 4s infinite; 
            transition: all .1s linear;
        }
        @keyframes anime{


            to{
                transform: rotate(3600deg);
            }
        }
    </style>
    <body>
        <div id="measurements">

            <p>Enter Measurement Below</p>    
            <div id="spinners">
                <img src="icon-spinner.png">
            </div>
            <form id="m-form" action="process.php" method="POST">
                <label for="length">Length
                    <input type="text" name="length" id="length" >
                </label>
                <label for="length">Width
                    <input type="text" name="width" id="width" >
                </label>
                <label for="length">Height
                    <input type="text"  name="height" id="height" >
                </label>
                <input id="html_submit" type="submit" value="Html Submit">
                <input id="ajax_submit" type="button" value="Ajax Submit">
            </form>
            <div id="results">
                <p>The total Volume is:
                    <span id="volume"></span>
                </p>

            </div>
            <div id="errors">
                <p>The are Errors
                    <span id="err"></span>
                </p>

            </div>
        </div>

        <script>
            var ajButton = document.querySelector("#ajax_submit");
            var results_div = document.querySelector("#results");
            var volume = document.querySelector("#volume");
            var spinners = document.querySelector("#spinners");
            var errors_div = document.querySelector("#errors");
            var err = document.querySelector("#err");
            function postResults(value) {
                volume.innerHTML = value;
                results_div.style.display = "block";

                let mForm = document.querySelector("#m-form");
                let inputs = mForm.getElementsByTagName('input');

                for (x = 0; x < inputs.length; x++) {
                    let  input = inputs[x];
                    input.classList.remove('error');
                }
            }
            function  postErrors(er) {
                err.innerHTML = er;
                errors_div.style.display = "block";
                //-------------------------------------------------------------------
                let mForm = document.querySelector("#m-form");
                let inputs = mForm.getElementsByTagName('input');

                for (x = 0; x < inputs.length; x++) {
                    let  input = inputs[x];
                    if (er.indexOf(input.name) >= 0) {

                        input.classList.add('error');
                    } else {
                        input.classList.remove('error');
                    }
                }
                // input.classList.add('error');

            }
            function clearResults() {
                volume.innerHTML = '';
                results_div.style.display = "none";
                err.innerHTML = '';
                errors_div.style.display = "none";
            }
// textInputs, select-options, checkboxes, radio buttons
            function collectForm(myForm) {

                var inputs = myForm.getElementsByTagName('input');
                var array = [];
                for (i = 0; i < inputs.length; i++) {
                    var inputVal = inputs[i].name + '=' + inputs[i].value;
                    array.push(inputVal);
                }
                return array.join('&');
            }
            function  calcMeasurements() {
                ajButton.disabled = true;

                ajButton.value = 'Submited';
                clearResults();
                spinners.style.display = 'block';
                var xhr = new XMLHttpRequest();
                var form = document.querySelector("#m-form");
                //determine form url
                var url = form.getAttribute("action");
                //gather form data

//                var formData = collectForm(form); or->
                var form_data = new FormData(form);
                for ([key, value] of form_data.entries()) {
                    console.log(key + ': ' + value);
                }

                xhr.open('POST', url, true);

                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                //FormData sets its own content type
                //               xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = () => {

                    if (xhr.status == 200 && xhr.readyState == 4) {
                        ajButton.disabled = false;
                        ajButton.value = 'Ajax Button';
                        spinners.style.display = 'none';
                        var result = xhr.responseText;
                        var json = JSON.parse(result);

                        if (json.hasOwnProperty('errors') && json.errors.length > 0) {
                            postErrors(json.errors);
                        } else {
                            console.log("Results: " + result);
                            postResults(json.volume);
                        }


                    }
                };
                // xhr.send(formData);\\
                xhr.send(form_data);
            }

            ajButton.addEventListener('click', calcMeasurements);

        </script>
    </body>
</html>
