<?php header("Content-Type: text/html; charset=utf-8");

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, "es_ES.UTF-8");
define("USER", "user");
define("ADMIN", "admin");
define("STORAGE", "storage" . DIRECTORY_SEPARATOR);
define("MAXFILESIZE", 1073741824); //1G
define("MAXUSERSIZE", 1073741824); //20G
//21474836480); //20G
// upload_max_filesize = 1024M
// post_max_size = 1024M
// max_file_uploads = 20

const allowedExtensions = 
[
    "jpg",
    "jpeg",
    "png",
    "gif",
    "mp4",
    "mp3",
    "pdf",
    "txt",
    "mpeg",
    "webp",
    "zip",
    "bin",
];

?>