<?php

    $bot_token = "618572383:AAGzsmzntRDrktR_FLdUFCS6wgU-WZsvbJA";
    $chat_id = "-1001385149154"; //Trading Desk Signals chat id
    $url = "https://api.telegram.org/bot$bot_token/sendmessage?"; //chat_id=$chat_id&text=

    $channel1 = "1175987244"; // Cindicator Bot Owners (feed) channel

    $cmd = "/home/vov/projects/tg/bin/telegram-cli --json -k --disable-link-preview -I -R -C -N";

    $descriptorspec = array(
       0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
       1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
       2 => array("pipe", "w")    // stderr is a pipe that the child will write to
    );
    flush();
    $process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), array());

    $old_id = "";
    if (is_resource($process)) {
        while ($s = fgets($pipes[1])) {
            if (substr($s, 0, 1) == "{") {
                print "\n**************************new message********************************\n";
                $data = json_decode($s, true);
                if ($data["from"]["peer_id"] == $channel1 && $data["id"] != $old_id) {
                    print $data["text"];
                    $options = array(
                        'chat_id' => $chat_id,
                        'text' => $data["text"]
                    );
                    file_get_contents($url . http_build_query($options));
                    $old_id = $data["id"];
                }
            }
            flush();
        }
    }
?>