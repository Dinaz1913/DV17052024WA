<?php

declare(strict_types=1);
/**  To Enter The Program Must Valid Password **/
function isValidPassword(string $password): bool {
    return $password === 'shakespear12';
}

function generateAndSaveApiKey(): bool {

    $data1 = ['540'];
    $data2 = ['098'];
    $data3 = ['5f7'];
    $data4 = ['3d9'];
    $data5 =['f86'];
    $data6 = ['054'];
    $data7 = ['e69'];
    $data8 = ['8c6'];
    $data9 = ['ce6'];
    $data10 = ['b61'];
    $data11= ['ad'];


    $mergedData = array_merge($data1, $data2, $data3, $data4, $data5, $data6,
    $data7, $data8, $data9, $data10, $data11);
    $mergedData1 = implode('', $mergedData);
    $mergedData2 =  [
        "apiKey" => $mergedData1
    ];


    $json = json_encode($mergedData2, JSON_PRETTY_PRINT);


    return file_put_contents('api_key.json', $json) !== false;
}


$password = readline("Enter password to generate and save API key: ");

if (!isValidPassword($password)) {
    echo "Incorrect password. Access denied.\n";
    exit(1);
}

if (generateAndSaveApiKey()) {
    echo "API key generated and saved successfully.\n";
} else {
    echo "Failed to save API key.\n";
}

$password = readline("Enter password to access API key: ");

$apiKeyData = file_get_contents('api_key.json');
$apiKeyJson = json_decode($apiKeyData, true);
$apiKey = $apiKeyJson['apiKey'];
$cityName = strtolower(trim(readline("Enter city:")));


$sourceForLangLong = "http://api.openweathermap.org/geo/1.0/direct";
$siteForLocation = "$sourceForLangLong?q=$cityName&limit=5&appid=$apiKey";


$response = file_get_contents($siteForLocation);
$data = json_decode($response, true);

if (!empty($data)) {
    $latitude = $data[0]['lat'];
    $longitude = $data[0]['lon'];
    echo "Latitude: $latitude, Longitude: $longitude\n\n";

    $sourceForWeather = "https://api.openweathermap.org/data/2.5/weather";
    $siteForWeather = "$sourceForWeather?lat=$latitude&lon=$longitude&appid=$apiKey";
    $response1 = file_get_contents($siteForWeather);
    $data2 = json_decode($response1, true);

    if (!empty($data2)) {
        $place = $data2['name'];
        $temperatureInCelsius = $data2['main']['temp'] - 273.15;
        $windSpeed = $data2['wind']['speed'];
        $sky = $data2['weather'][0]['description'];
        $humidity = $data2['main']['humidity'];
        $pressure = $data2['main']['pressure'];

        echo "In $place temperature is $temperatureInCelsius °C\n";
        echo "in $place condition here is   $sky and wind speed is $windSpeed m/s\n";
        echo "The average humidity is $humidity % and average pressure is $pressure hPa\n\n";
    } else {
        echo "Error retrieving weather data.\n\n";
    }
} else {
    echo "Error retrieving location data.\n\n";
}
