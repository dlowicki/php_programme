
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Programme</title>
  </head>
  <body>
    <style>

    body {
        background-color: darkgray;
        width: 60vw;
        margin-left: auto;
        margin-right: auto;
        font-family: sans-serif;
        font-size: 1.2vw;
    }

      text {
        color: blue;
      }

      span {
        color: red;
      }

      #msg_update {
        text-align:center;
        font-size: 1.4vw;
      }

      #msg_log {
        text-align: left;
        width: 55vw;
        margin-left: auto;
        margin-right: auto;
        height: 50vh;
        overflow: auto;
      }

      #last {
        position: absolute;
        right: 2vw;
        top: 0;
      }

      #current {
        position: absolute;
        left: 2vw;
        top: 0;
      }

      table {
        margin-left: auto;
        margin-right: auto;
        margin-top: 2vw;
      }


    </style>



    <?php
    function getVersionChip($url) {
      $source = file_get_contents($url);

      $doc = new DOMDocument;
      @$doc->loadHTML($source);

      $xpath = new DOMXPath($doc);
      $classname = "dl-version";
      $elements = $xpath->query("//*[contains(@class, '$classname')]");
      $version = "";

      foreach ($elements as $e) {
        $version = $e->ownerDocument->saveXML($e);
      }
      $tmp = explode(">", $version);
      $tmp2 = explode("<", $tmp[1]);

      return $tmp2[0];
    }

    function getLastTime() {
      $time = parse_ini_file("config.ini");
      return $time['time'];
    }

    function getCurrentTime() {
      return date("H:i:s");
    }

    function getCurrentDate() {
      return date("d:m:Y - H:i:s");
    }

    function editConfig() {
      $handle = fopen("config.ini", "w");
      fwrite($handle, "time=" . getCurrentDate());
      fclose($handle);
    }

    function editVersions() {

      $urls = array(
        "CCleaner" => "https://www.chip.de/downloads/CCleaner_16317939.html",
        "Notepad++" => "https://www.chip.de/downloads/Notepad_12996935.html",
        "PDFCreator" => "https://www.chip.de/downloads/PDFCreator_13009777.html",
        "PDF24" => "https://www.chip.de/downloads/PDF24-Creator_43805654.html",
        "VLC Media Player" => "https://www.chip.de/downloads/VLC-player-64-Bit_53513913.html",
        "7-Zip" => "https://www.chip.de/downloads/7-Zip-64-Bit_38851222.html",
        "IrfanView" => "https://www.chip.de/downloads/IrfanView-64-Bit_81722226.html",
        "Adobe Acrobat Reader DC" => "https://www.chip.de/downloads/Adobe-Acrobat-Reader-DC_12998358.html",
        "Adobe Flash Player" => "https://www.chip.de/downloads/Adobe-Flash-Player_13003561.html"
      );

      $versions = "CCleaner=" . getVersionChip($urls["CCleaner"]) . "\r\n" .
      "Notepad++=" . getVersionChip($urls["Notepad++"]) . "\r\n" .
      "PDFCreator=" . getVersionChip($urls["PDFCreator"]) . "\r\n" .
      "PDF24=" . getVersionChip($urls["PDF24"]) . "\r\n" .
      "VLC Media Player=" . getVersionChip($urls["VLC Media Player"]) . "\r\n" .
      "7-Zip=" . getVersionChip($urls["7-Zip"]) . "\r\n" .
      "IrfanView=" . getVersionChip($urls["IrfanView"]) . "\r\n" .
      "Adobe Acrobat Reader DC=" . getVersionChip($urls["Adobe Acrobat Reader DC"]) . "\r\n" .
      "Adobe Flash Player=" . getVersionChip($urls["Adobe Flash Player"]) . "\r\n";

      $oldVersions = outputVersions();
      $newVersion = array();
      $programs = array("CCleaner","Notepad++","PDFCreator","PDF24","VLC Media Player","7-Zip","IrfanView","Adobe Acrobat Reader DC","Adobe Flash Player");

        // Geht jedes einzelne Program durch
        for($r=0;$r<sizeof($programs);$r++){
          $neue = getVersionChip($urls[$programs[$r]]);
          //echo "<br>Wenn " . $oldVersions[$programs[$r]] . " nicht gleich " . $neue . "<br>";
          if($oldVersions[$programs[$r]] != $neue){
            $newVersion[$r] = $programs[$r] . " aktualisiert von <text>" . $oldVersions[$programs[$r]] . "</text> auf <span>" . $neue . "</span><br>";
          }
        }

        if(sizeof($newVersion) == 0){
          echo "<div id='msg_update'>Es wurden seit der letzten Aktualisierung keine Updates veröffentlicht</div>";
        } else {
          foreach ($newVersion as $key) {
            setLogUpdate($key);
          }
        }

      $handle = fopen("versions.ini", "w");
      fwrite($handle, $versions);
      fclose($handle);
      editConfig();

      echo "<div id='time'>
      <p id='current'></p>
      <p id='last'>Letzte Aktualisierung am " . getLastTime() . " Uhr</p>
      </div>";
    }

    function outputVersions() {
      $versions = parse_ini_file("versions.ini");
      return $versions;
    }

    function outputUpdate(){
      $update = parse_ini_file("update.ini");
      return $update;
    }

    function setLogUpdate($text) {
      $str = "";

      if($text != ""){
        $str = getCurrentDate() . "=" . $text . "\r\n";
        sleep(1);
      } else {
        foreach ($text as $key) {
          $str = getCurrentDate() . "=" . $str . $key . "\r\n";
          sleep(1);
        }
      }


      $handle = fopen("update.ini", "a");
      fwrite($handle, $str);
      fclose($handle);
    }

    /*function createSQLConnection() {
      $servername = 'vm-s-ka-sql';
      $data = array("Database" => "ACMP201206110953", "UID" => "sa", "PWD" => "aagon@12");
      $conn = sqlsrv_connect($servername, $data);
      //$conn = mssql_connect($servername, "sa", "aagon@12");

      if($conn){
        echo "Verbindng aufgebaut";
      } else {
        echo "Verbindung konnte nicht aufgebaut werden";
        die( print_r( sqlsrv_errors(), true));
      }
    }*/


      //echo "Wenn " . ((int)getLastTime()+60) . " kleiner als " . (int)getCurrentTime() . " ist<br>";
      //echo "Noch " . ((int)getLastTime()+60-(int)getCurrentTime() . " Sekunden");


      $versions = outputVersions();

      echo "<table>";
      echo "<tr><td>CCleaner</td><td>" . $versions["CCleaner"] . "</td></tr>";
      echo "<tr><td>Notepad++</td><td>" . $versions["Notepad++"] . "</td></tr>";
      echo "<tr><td>PDFCreator</td><td>" . $versions["PDFCreator"] . "</td></tr>";
      echo "<tr><td>PDF24</td><td>" . $versions["PDF24"] . "</td></tr>";
      echo "<tr><td>VLC Media Player</td><td>" . $versions["VLC Media Player"] . "</td></tr>";
      echo "<tr><td>7-Zip</td><td>" . $versions["7-Zip"] . "</td></tr>";
      echo "<tr><td>IrfanView</td><td>" . $versions["IrfanView"] . "</td></tr>";
      echo "<tr><td>Adobe Acrobat Reader DC</td><td>" . $versions["Adobe Acrobat Reader DC"] . "</td></tr>";
      echo "<tr><td>Adobe Flash Player</td><td>" . $versions["Adobe Flash Player"] . "</td></tr>";
      echo "</table>";

      $update = outputUpdate();
      echo "<hr>";
      echo "<div id='msg_log'>";

      foreach ($update as $key) {
        echo $key;
      }

      echo "</div>";
      echo "<hr>";

    //if(getLastTime()+60 < getCurrentTime()){
      //createSQLConnection();
      editVersions();

    //}

    ?>
    <script type="text/javascript">
      var today = new Date();
      var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
      var date = today.getDate() + "-" + (today.getMonth()+1) + "-" + today.getFullYear();

      document.getElementById("current").innerHTML = "Uhrzeit: " + date + " - " + time + " Uhrzeit";

      setInterval(function(){
        var today = new Date();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var date = today.getDate() + "-" + (today.getMonth()+1) + "-" + today.getFullYear();

        document.getElementById("current").innerHTML = "Uhrzeit: " + date + " - " + time + " Uhrzeit";

      },1000);

    </script>
  </body>
</html>
