<?php
//Lingua Robot

function getWordInfo(string $word): ?array
{
    //Заменяю пробел на %2520, чтобы запрос фраз проходил корректно
    $word = str_replace(' ', '%20', $word);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://lingua-robot.p.rapidapi.com/language/v1/entries/en/$word",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "x-rapidapi-host: lingua-robot.p.rapidapi.com",
            "x-rapidapi-key: 99d6039723mshcaa6930780810d7p173043jsna4ae1d23e3de"
        )
    ));

    $response = curl_exec($curl);
    $curlError = curl_error($curl);

    curl_close($curl);

    if ($curlError)
    {
        echo "cURL Error #:" . $curlError;
    }
    else
    {
        $response = json_decode($response, true);

        $entries = $response["entries"][0];
        //Если слово существует, то получить данные
        if (!empty($entries))
        {
            $pronunciations = getPronunciations($entries);
            $definitionsByPartOfSpeech = getDefinitionsByPartOfSpeech($entries);

            return array(
                "wordIsCorrect" => true,
                "pronunciations" => $pronunciations,
                "definitionsByPartOfSpeech" => $definitionsByPartOfSpeech
            );
        }
        else
        {
            return array(
                "wordIsCorrect" => false
            );
        }
    }
}

//Функция получает транкрипции и аудио английского и американского произношений слова
function getPronunciations(array $entries): ?array
{
    $pronunciations = $entries["pronunciations"];

    foreach ($pronunciations as $pronunciation)
    {
        if ($pronunciation["context"]["regions"][0] === "United Kingdom")
        {
            $transcriptionUK = end($pronunciation["transcriptions"])["transcription"];
            if (isset($pronunciation["audio"]))
            {
                $audioUK = $pronunciation["audio"]["url"];
            }
        }
        elseif ($pronunciation["context"]["regions"][0] === "United States")
        {
            $transcriptionUS = end($pronunciation["transcriptions"])["transcription"];
            if (isset($pronunciation["audio"]))
            {
                $audioUS = $pronunciation["audio"]["url"];
            }

            break;
        }
    }

    return array(
        "transcriptionUK" => $transcriptionUK,
        "transcriptionUS" => $transcriptionUS,
        "audioUK" => $audioUK,
        "audioUS" => $audioUS
    );
}

function getDefinitionsByPartOfSpeech(array $entries): ?array
{
    $lexemes = $entries["lexemes"];
    $definitionsByPartOfSpeech = [];

    foreach ($lexemes as $lexeme)
    {
        $partOfSpeech = $lexeme["partOfSpeech"];
        $definitions = getDefinitions($lexeme);

        $definitionsByPartOfSpeech["$partOfSpeech"] = $definitions;
    }

    return $definitionsByPartOfSpeech;
}

function getDefinitions(array $lexeme): ?array
{
    $senses = $lexeme["senses"];
    $definitions = [];
    $index = 0;

    foreach ($senses as $sense)
    {
        if ($index !== 3)
        {
            $definitions[$index] = array(
                "definition" => $sense["definition"]
            );
            if (!empty($sense["usageExamples"]))
            {
                $definitions[$index]["usageExample"] = $sense["usageExamples"][0];
            }

            $index++;
        }
    }

    return $definitions;
}
?>