<?php
//Lingua Robot
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://lingua-robot.p.rapidapi.com/language/v1/entries/en/father",
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
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}
/*$response = '{"entries":[{"entry":"example","pronunciations":[{"transcriptions":[{"transcription":"/əɡˈzæmpl̩/","notation":"IPA"}],"context":{"regions":["Australia"]}},{"transcriptions":[{"transcription":"/ɘɡˈzɐːmpɯ/","notation":"IPA"}],"context":{"regions":["New Zealand"]}},{"transcriptions":[{"transcription":"/ɪɡˈzɑːmpl̩/","notation":"IPA"}],"context":{"regions":["United Kingdom"]}},{"transcriptions":[{"transcription":"/əɡˈzæmpl̩/","notation":"IPA"},{"transcription":"/ɪɡˈzæmpl̩/","notation":"IPA"}],"audio":{"url":"http://audio.linguarobot.io/en/example-us.mp3","sourceUrl":"https://commons.wikimedia.org/w/index.php?curid=267013"},"context":{"regions":["United States"]}}],"interpretations":[{"lemma":"example","normalizedLemmas":[{"lemma":"example"}],"partOfSpeech":"noun","grammar":[{"number":["singular"],"case":["nominative"]}]},{"lemma":"example","normalizedLemmas":[{"lemma":"example"}],"partOfSpeech":"verb","grammar":[{"verbForm":["infinitive"]},{"person":["first-person","second-person","third-person"],"number":["plural"],"verbForm":["finite"],"tense":["present"],"mood":["indicative"]},{"person":["first-person","second-person","third-person"],"number":["singular","plural"],"verbForm":["finite"],"mood":["imperative"]},{"person":["first-person","second-person"],"number":["singular"],"verbForm":["finite"],"tense":["present"],"mood":["indicative"]},{"person":["first-person","second-person","third-person"],"number":["singular","plural"],"verbForm":["finite"],"tense":["present"],"mood":["subjunctive"]}]}],"lexemes":[{"lemma":"example","partOfSpeech":"noun","senses":[{"definition":"Something that is representative of all such things in a group.","labels":["countable"]},{"definition":"Something that serves to illustrate or explain a rule.","labels":["countable"]},{"definition":"Something that serves as a pattern of behaviour to be imitated (a good example) or not to be imitated (a bad example).","labels":["countable"]},{"definition":"A person punished as a warning to others.","labels":["countable"]},{"definition":"A parallel or closely similar case, especially when serving as a precedent or model.","labels":["countable"]},{"definition":"An instance (as a problem to be solved) serving to illustrate the rule or precept or to act as an exercise in the application of the rule.","labels":["countable"]}],"forms":[{"form":"examples","grammar":[{"number":["plural"],"case":["nominative"]}]}]},{"lemma":"example","partOfSpeech":"verb","senses":[{"definition":"To be illustrated or exemplified (by)."}],"forms":[{"form":"exampled","grammar":[{"verbForm":["participle"],"tense":["past"]},{"person":["first-person","second-person","third-person"],"number":["singular","plural"],"verbForm":["finite"],"tense":["past"],"mood":["indicative"]}]},{"form":"examples","grammar":[{"person":["third-person"],"number":["singular"],"verbForm":["finite"],"tense":["present"],"mood":["indicative"]}]},{"form":"exampling","grammar":[{"verbForm":["gerund"]},{"verbForm":["participle"],"tense":["present"]}]}]}],"license":{"name":"CC BY-SA 3.0","url":"https://creativecommons.org/licenses/by-sa/3.0"},"sourceUrls":["https://en.wiktionary.org/wiki/example"]}]}';
$ins = json_decode($response, true);
printResponse($ins);
function printResponse($ins)
{
    foreach ($ins as $key=>$value)
    {
        echo $key . '->' . $value . '<br>';
        printResponse($value);
    }
}
*/
?>