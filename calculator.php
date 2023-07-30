<?php

// Initialize variables
$currentValue = 0;
$input = [];

// Function to concatenate the input array into a string
function getInputAsString($values){
    $o = "";
    foreach ($values as $value){
        $o .= $value;
    }
    return $o;
}

// Function to calculate the result of the user input
function calculateInput($userInput){
    // Format user input
    $arr = [];
    $char = "";
    foreach ($userInput as $num){
        if(is_numeric($num) || $num == "."){
            $char .= $num;
        } else if(!is_numeric($num)){
            if(!empty($char)){
                $arr[] = $char;
                $char = "";
            }
            $arr[] = $num;
        }
    }
    if(!empty($char)){
        $arr[] = $char;
    }

    // Calculate user input
    $current = 0;
    $action = null;
    for($i=0; $i<= count($arr)-1; $i++){
        if(is_numeric($arr[$i])){
            if($action){
                if($action == "+"){
                    $current = $current + $arr[$i];
                }
                if($action == "-"){
                    $current = $current - $arr[$i];
                }
                if($action == "x"){
                    $current = $current * $arr[$i];
                }
                if($action == "/"){
                    $current = $current / $arr[$i];
                }
                $action = null;
            } else{
                if($current == 0){
                    $current = $arr[$i];
                }
            }
        } else{
            $action = $arr[$i];
        }
    }
    return $current;
}

// Check if the 'REQUEST_METHOD' index exists in $_SERVER array
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['input'])){
        $input = json_decode($_POST['input']);
    }

    // Process the input based on button presses
    if(isset($_POST)){
        foreach ($_POST as $key=>$value){
            // When the "equal" button is pressed, calculate the result
            if($key == 'equal'){
                $currentValue = calculateInput($input);
                $input = [];
                $input[] = $currentValue;
            }
            // When the "ce" (Clear Entry) button is pressed, remove the last input
            elseif($key == "ce"){
                array_pop($input);
            }
            // When the "c" (Clear) button is pressed, reset the input and current value to 0
            elseif($key == "c"){
                $input = [];
                $currentValue = 0;
            }
            // When the "back" button is pressed, remove the last digit from the input
            elseif($key == "back"){
                $lastPointer = count($input) - 1;
                if(is_numeric($input[$lastPointer])){
                    array_pop($input);
                }
            }
            // For other buttons (numbers and operators), add the corresponding value to the input array
            elseif($key != 'input'){
                $input[] = $value;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Calculator</title>
</head>
<body>
<h3>My Calculator</h3>
<div style="border: 1px solid #ccc; border-radius: 3px; padding: 5px; display: inline-block">
    <form method="post">
    <input type="hidden" name="input" value='<?php echo json_encode($input);?>'/>
    <!-- Display the current input as a string -->
    <p style="padding: 3px; margin: 0; min-height: 20px;"><?php echo getInputAsString($input);?></p>
    <!-- Display the current value as a result -->
    <input type="text" value="<?php echo $currentValue;?>"/>
    <table>
        <tr>
            <!-- Buttons for Clear Entry, Clear, Back, and Division -->
            <td><input type="submit" name="ce" value="CE"/></td>
            <td><input type="submit" name="c" value="C"/></td>
            <td><button type="submit" name="back" value="back">&#8592;</button></td>
            <td><button type="submit" name="divide" value="/">&#247;</button></td>
        </tr>
        <tr>
            <!-- Buttons for numbers 7, 8, 9, and Multiplication -->
            <td><input type="submit" name="7" value="7"/></td>
            <td><input type="submit" name="8" value="8"/></td>
            <td><input type="submit" name="9" value="9"/></td>
            <td><input type="submit" name="multiply" value="x"/></td>
        </tr>
        <tr>
            <!-- Buttons for numbers 4, 5, 6, and Subtraction -->
            <td><input type="submit" name="4" value="4"/></td>
            <td><input type="submit" name="5" value="5"/></td>
            <td><input type="submit" name="6" value="6"/></td>
            <td><input type="submit" name="minus" value="-"/></td>
        </tr>
        <tr>
            <!-- Buttons for numbers 1, 2, 3, and Addition -->
            <td><input type="submit" name="1" value="1"/></td>
            <td><input type="submit" name="2" value="2"/></td>
            <td><input type="submit" name="3" value="3"/></td>
            <td><input type="submit" name="add" value="+"/></td>
        </tr>
        <tr>
            <!-- Buttons for Plus/Minus toggle, number 0, Decimal point, and Equal -->
            <td><button type="submit" name="plusminus" value="plusminus">&#177;</button></td>
            <td><input type="submit" name="zero" value="0"/></td>
            <td><input type="submit" name="." value="."/></td>
            <td><input type="submit" name="equal" value="="/></td>
        </tr>
    </table>
    </form>
</div>

</body>
</html>
