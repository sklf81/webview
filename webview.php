<html>
<head>
    <meta charset="utf-8">
    <title>Dir-Webview</todo>
</head>
<body>
</body>
<?php

    function echoFiles($dir){
        $dir_arr = scandir($dir);
        foreach($dir_arr as $entry){
            if(is_dir($entry)){
                $directories[-1] = $entry;
            }
            else{
                $files[-1] = $entry;
            }
        }
        $html_directory = "";
        
    }

    $dir = "/";
    echoFiles($dir);

?>


</html>