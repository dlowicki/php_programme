
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <script src="jquery.min.js"></script>
    <script src="functions.js"></script>
    <title>Programme</title>
    <?php
    session_start();
      if(isset($_GET['style'])){
        if($_GET['style'] == "modern"){
          echo '<link rel="stylesheet" href="style/modern.css">';
          $_SESSION['style'] = "modern";
        } else {
          echo '<link rel="stylesheet" href="style/compact.css">';
          $_SESSION['style'] = "compact";
        }
      }
    ?>
  </head>
  <body>
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

    function getUrls() {
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
      return $urls;
    }

    function editVersions() {

      $urls = getUrls();

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
          if($oldVersions[$programs[$r]] != $neue){
            $newVersion[$r] = $programs[$r] . " aktualisiert von <text>" . $oldVersions[$programs[$r]] . "</text> auf <span>" . $neue . "</span><br>";
          }
        }

        if(sizeof($newVersion) == 0){
          echo "<div id='msg_update'><p>Es wurde seit der letzten Aktualisierung kein Update veröffentlicht</p></div>";
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
      <div id='menu'>
        <ul>";
        if($_SESSION['style'] == "modern"){
          echo "<li><a href='index.php?style=modern' class='nav_current'>Modern</a></li>
          <li><a href='index.php?style=compact' style='color: black;'>Kompakt</a></li>";
        } else {
          echo "<li><a href='index.php?style=modern' style='color: black;'>Modern</a></li>
          <li><a href='index.php?style=compact' class='nav_current'>Kompakt</a></li>";
        }
        echo "
        </ul>
      </div>
      <div id='last'><p id='last_update'>Letzte Aktualisierung am " . getLastTime() . " Uhr </p></div>
        <div id='msg_ak'>
          <div class='spinner'>
            <div class='bounce1'></div>
            <div class='bounce2'></div>
            <div class='bounce3'></div>
          </div>
        </div>
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

      $versions = outputVersions();
      echo "<div id='table'>";
      echo "<div class='container'>";
      echo "<h2>Die neusten Versionen</h2>";
      echo "<table id='table_content'>";
      echo "<tr><td>Notepad++</td><td>" . $versions["Notepad++"] . "</td></tr>";
      echo "<tr><td>PDFCreator</td><td>" . $versions["PDFCreator"] . "</td></tr>";
      echo "<tr><td>PDF24</td><td>" . $versions["PDF24"] . "</td></tr>";
      echo "<tr><td>VLC Media Player</td><td>" . $versions["VLC Media Player"] . "</td></tr>";
      echo "<tr><td>7-Zip</td><td>" . $versions["7-Zip"] . "</td></tr>";
      echo "<tr><td>IrfanView</td><td>" . $versions["IrfanView"] . "</td></tr>";
      echo "<tr><td>Adobe Acrobat Reader DC</td><td>" . $versions["Adobe Acrobat Reader DC"] . "</td></tr>";
      echo "<tr><td>Adobe Flash Player</td><td>" . $versions["Adobe Flash Player"] . "</td></tr>";
      echo "<tr><td>CCleaner</td><td>" . $versions["CCleaner"] . "</td></tr>";
      echo "</table>";
      echo "<div id='tr_drop'>
              <img src='img/x.ico' id='tr_back'>
              <h3 id='tr_drop_headline'>Dein Name und Version</h3>
              <img src='img/pdf24.jpg' id='tr_img'>
              <p id='tr_drop_description'>Beschreibung abc bla bla bla</p>
              <h4 id='tr_drop_delete'>Möchten Sie NAME wirklich entfernen?</h4>
              <button>Entfernen</button>
            </div>";
      echo "</div>";
      echo "</div>";

      $update = outputUpdate();

      echo "<hr>";
      echo "<div id='msg_log'>";
      echo "<div class='container'>";
      echo "<h2>Aktualisierungs Log</h2>";
        echo "<div id='msg_log_update'>";
          foreach ($update as $key => $value) {

            $urls = getUrls();

            foreach ($urls as $key2 => $value2) {

              $exp = explode(" aktualisiert", $value);

              if($exp[0] == $key2){
                echo "<a class='msg_log_eintrag' href='$value2'>[" . $key . "] " . $value . "</a>";
              }
            }
          }
        echo "</div>";

        echo "</div>";
      echo "</div>";
      echo "<hr>";



      echo  "<div id='create_program'>
              <div class='container'>
                <h2>Programm hinzufügen</h2>
                <form method='POST' id='form_create' action='create.php'>
                  <p>Chip URL zur Programmseite</p>
                  <input type='text' name='url' id='form_url'>
                  <p>Logo des Programms</p>
                  <input type='file' id='form_file' accept='image/jpg' required>
                  <p>Name des Programms</p>
                  <input type='text' name='url' id='form_title' readonly>
                  <input type='submit' name='create_button' class='form_button' id='form_button' value='Name erstellen' style='display: block;'>
                  <input type='submit' name='create_button' class='form_button' id='form_button2' value='Senden' style='display: none;'>
                </form>
                </div>
            </div>";
/*
  https://www.chip.de/downloads/Adobe-Acrobat-Reader-DC_12998358.html
  https://www.chip.de/downloads/VLC-player-64-Bit_53513913.html
*/




      editVersions();

    ?>
    <script type="text/javascript">

    $("#form_create").submit(function(event){
      event.preventDefault();

      var title_style = document.getElementById("form_button").style.display;
      if(title_style == "block"){
        var url = $("#form_url").val();

        if(!url.includes("https://www.chip.de/downloads/")){
          alert("Keine richtige URL angegeben!");
          $("#form_url").val("");
          return;
        }

        var spl = url.split("https://www.chip.de/downloads/");
        if(!(spl[1].length >= 13)){
          alert("Die URL wurde nicht gefunden!");
          return;
        }
        var anyString4 = spl[1].substring(spl[1].length - 5);
        if(anyString4 != ".html"){
          alert("Die URL wurde nicht gefunden!");
          return;
        }

        console.log(spl[1]);
        var spl2 = spl[1].split("_");
        var spl3 = spl2[0].replace(/-/g, " ");


        var correctname;
        if(spl3.includes("64")){
          correctname = spl3.split("64");
        } else if(spl3.includes("32")) {
          correctname = spl3.split("32");
        } else {
          correctname = spl3;
        }

        $("#form_title").val(correctname[0]);

          document.getElementById("form_button").style.display = "none";
          document.getElementById("form_button2").style.display = "block";

        document.getElementById("form_title").readOnly = false;
        return;
      }


      if($("#form_title").val().length > 5){
        var name = $("#form_title").val();

        var file_data = $('#form_file').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);

        $.ajax({
          url: "api/upload.php",
          method: "POST",
          data: form_data,
          processData: false,
          contentType: false,
          cache: false,
          success: function(result) {
            if(result == "error"){
              alert("Ein Fehler ist aufgetreten");
              return;
            } else if (result == "format") {
              alert("Es werden nur .jpg Formate hochgeladen!");
              return;
            }

              $.ajax({
                url: "api/upload.php",
                method: "POST",
                data: {uploaded: "12345", title: name},
                success: function(result) {
                  alert(result);
                  $("#form_title").val("");
                  $("#form_file").val("");
                  $("#form_url").val("");
                  document.getElementById("form_button").style.display = "block";
                  document.getElementById("form_button2").style.display = "none";
                }
              });
          }
        });
      } else {
        alert("Der Name muss länger als 5 Zeichen lang sein!");
      }





    });

    $("#tr_back").click(function(){
      close_tr();
    });

    $("td").click(function(){
      var content = $(this).parent().text();
      var splitted = content.split("Version");

      $.getJSON('sync.php', function (data) {

         $.each(data, function (name, value) {
            if(name == splitted[0]){
              open_tr(splitted[0], splitted[1], value);
            }
         });
      });
    });



    function sleep (time) {
      return new Promise((resolve) => setTimeout(resolve, time));
    }

    setInterval(function() {
        document.getElementById("msg_ak").style.display = "block";
        var last = document.getElementById("last_update").innerHTML;

        var today2 = new Date();
        var time2 = today2.getHours() + ":" + today2.getMinutes() + ":" + today2.getSeconds();
        $("#last_update").load(location.href + " #last_update");

        $("#msg_log_update").load(location.href + " #msg_log_update");

        sleep(3000).then(() => {
            document.getElementById("msg_ak").style.display = "none";
        });

      }, 300000);

      var today = new Date();
      var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
      var date = today.getDate() + "-" + (today.getMonth()+1) + "-" + today.getFullYear();

      document.getElementById("current").innerHTML = "Uhrzeit: " + date + " - " + time + " Uhr";

      setInterval(function(){
        var today = new Date();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var date = today.getDate() + "-" + (today.getMonth()+1) + "-" + today.getFullYear();

        document.getElementById("current").innerHTML = "Uhrzeit: " + date + " - " + time + " Uhr";

      },1000);

    </script>
  </body>
</html>
