<?php
    require_once 'simple_html_dom.php';

    $url = 'https://en.wiktionary.org/wiki/father?printable=yes';
    $html = file_get_html($url);



    function getUKAndUSTranscriptions()
    {
        $listIndex = 0;
        $listStr = $html->find('#Pronunciation')[0]->parent()->next_sibling()->children[$listIndex]->plaintext;

        if (strstr($listStr, 'UK') !== false && strstr($listStr, 'US') !== false)
        {
            //Общий

        }
        elseif (strstr($listStr, 'UK') !== false || strstr($listStr, 'Received Pronunciation') !== false)
        {
            //UK
        }
        elseif (strstr($listStr, 'US') !== false || strstr($listStr, 'General American') !== false)
        {
            //US
        }
        else
        {
            //Общий
        }
    }

    function getUKTranscription()
    {
        $listStr
    }