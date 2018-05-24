<?php

$bot_token = "507867795:AAEpa4QEohbUrX_d1tM-jjpvaVyyD9kWv20";
$chat_id = "-1001217999752";
$url = "https://api.telegram.org/bot$bot_token/sendmessage?chat_id=$chat_id&text=";

$cmd = "/home/vov/projects/tg/bin/telegram-cli --json -k --disable-link-preview -I -R -C -N";

$descriptorspec = array(
   0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
   2 => array("pipe", "w")    // stderr is a pipe that the child will write to
);
flush();
$process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), array());
$i = 0;
$old_id = "";
if (is_resource($process)) {
    while ($s = fgets($pipes[1])) {
        if (substr($s, 0, 1) == "{") {
            $i++;
            print "*************************$i th******************************\n";
            $data = json_decode($s, true);
            if ($data["from"]["peer_id"] == "1190930272" && $data["id"] != $old_id) {
                print($data["text"]);
                file_get_contents($url . $data["text"]);
                $old_id = $data["id"];
            }
            flush();
        }
    }
}
?>