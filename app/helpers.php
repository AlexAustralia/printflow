<?php

function fixGetDate($value){
    // takes yyyy-mm-dd and returns dd/mm/yyyy

    // Change from dd/mm/yyyy to yyyy-mm-dd
    /*if (preg_match("@(\d{2})/(\d{2})/(\d{4})@", $value, $matches)){
        $value = $matches[3]."-".$matches[2]."-".$matches[1];
    }*/


    $obj = strtotime($value);
    if ($obj === false || $value == "0000-00-00") {
        return "";
    }

    $d = date("d/m/Y", $obj);

    return $d;
}

function fixSetDate($value){
    // Takes d(or dd)/m(or mm)/yy(or yyyy) and returns yyyy-mm-dd
    $return = "";

    if (preg_match("@(\d{1,2})/(\d{1,2})/(\d{4}|\d{2})@", $value, $matches)){
        $y = $matches[3];

        if (strlen($y) == 2){ # 1970 - 2069
            if ($y >= "70"){
                $y = "19$y";
            } else {
                $y = "20$y";
            }
        }

        $m = str_pad($matches[2], 2, "0", STR_PAD_LEFT);
        $d = str_pad($matches[1], 2, "0", STR_PAD_LEFT);

        $return = $y."-".$m."-".$d;
    }

    return $return;
}

function send_email($from, $replyTo, $to, $bcc, $subject, $body){

    Mail::raw($body, function($message){
        /*foreach (explode(", ", $input['from']) as $from){

            $pattern = '/(.*?)\<(.*?)\>/';
            preg_match($pattern, $from, $parts);
            dd($parts);
            $message->from($parts[0], $parts[1]);

        }
        die($message);
        $message->replyTo('art@franklindirect.com.au', 'David Thorne');
        $message->to('craig9@gmail.com');
        $message->subject($input['subject']);
        $message->bcc('craig@compufixtas.com.au');*/

        $message->to('craig9@gmail.com');
        $message->subject($subject);
        $message->bcc('craig@compufixtas.com.au');
    });
}
