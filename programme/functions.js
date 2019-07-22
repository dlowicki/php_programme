function close_tr(){
  document.getElementById("table_content").style.display = "block";
    document.getElementById("tr_drop").style.display = "none";
}

function open_tr(name, version, bs) {
  document.getElementById("tr_drop_headline").innerHTML = name + " mit der Version " + version;
  document.getElementById("tr_img").src = "img/"+name+".jpg";
  document.getElementById("tr_drop_description").innerHTML = bs;
  document.getElementById("tr_drop_delete").innerHTML = "Möchten Sie " + name + " wirklich entfernen?";

  document.getElementById("tr_drop").style.display = "block";
  document.getElementById("table_content").style.display = "none";
}
