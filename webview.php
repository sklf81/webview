<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="style.css"/>
        <link rel="icon" href="webview_icon.png"/>
        <title>webview: <?php
            $default_dir = getcwd();

            //Example: You want to use webview but your home directory should be a child directory of your cwd
            //$default_dir = getcwd()."/child_dir";

            //below is an example of a root directory from a webserver
            //if webview.php is located in your root directory, replace "/var/www/html" with getcwd()
            
            $server_root_dir = "/var/www/html";

            $dir = getDirectory($default_dir);
            $dir_disp = str_replace($default_dir, "/home", $dir);

            echo($dir_disp);
        ?>
        </title>
    </head>
    <body>
        <div id="gradient">
        </div>
        <h1>
            <?php
                echo($dir_disp);
            ?>
        </h1>
        <?php
            function getDirContent($dir){
                return scandir($dir);
            }

            function getFileNames($dir, $dir_content){
                $files = [];
                foreach($dir_content as $entry){
                    if(!is_dir($dir."/".$entry)){
                        $files[] = $dir."/".$entry;
                    }
                }
                return $files;
            }

            function getDirNames($dir, $dir_content){
                $dirs = [];
                foreach($dir_content as $entry){
                    if(is_dir($dir."/".$entry)){
                        $dirs[] = $dir."/".$entry;
                    }
                }
                return $dirs;
            }

            function echoDirs($directories){
                foreach($directories as $directory){
                    $tmp = removeParentDir($directory);
                    $html_directory_str = 
                    "
                    <div class='entry' name='dir_".$directory."'>
                        <div class='dir_entry'>
                            <form action='webview.php' method='post'>
                                <button type='submit' class='dir_button' name='directory' value='".$directory."' class='directorie_link'>".$tmp."</button>
                            </form> 
                        </div>
                    </div>
                    ";
                    echo($html_directory_str);
                }
            }

            function echoFiles($server_root_dir, $files){
                if(!empty($files)){
                    foreach($files as $file){
                        $tmp = removeParentDir($file);
                        $link = str_replace($server_root_dir, "", $file);
                        $html_file_str = 
                        "
                        <div class='entry' name='file_".$file."'>
                            <div class='file_entry'>
                                <a href='".$link."' name='file' class='file_link'>".$tmp."</a>
                            </div>
                        </div>
                        ";
                        echo($html_file_str);
                    }
                }
            }

            function getDirectory($default_dir){
                if(isset($_POST["directory"])){
                    $cur_dir = $_POST["directory"];
                    if(stringEndsWith($cur_dir, "/..")){
                        $dir = revertDirectory($cur_dir);
                        if(strpos($dir, $default_dir) === false){
                            return $default_dir;
                        }
                        else{
                            return $dir;
                        }
                    }
                    else if(stringEndsWith($cur_dir, "/.")){
                        $dir = substr($cur_dir, 0, strlen($cur_dir) - 2);
                        return $dir;
                    }
                    $dir = $_POST["directory"];
                }
                else{
                    $dir = $default_dir;
                }
                return $dir;
            }

            function stringEndsWith($haystack, $needle){
                $pos = strpos($haystack, $needle);
                return (substr($haystack, $pos, strlen($haystack) - $pos) == $needle);
            }

            function revertDirectory($dir){
                $length = strlen($dir);
                $pos2 = strrpos($dir, "/");
                $pos1 = strrpos(substr($dir, 0, $pos2), "/");
                return substr($dir, 0, $pos1);
            }

            function removeParentDir($dir){
                return str_replace(dirname($dir), "", $dir);
            }


            $dir_content = getDirContent($dir);
            $directories = getDirNames($dir, $dir_content);
            $files = getFileNames($dir, $dir_content);

            echoDirs($directories);
            echoFiles($server_root_dir, $files);
        ?>

    <div class="credits">
            <h2>Copyright © 2022 | Philip Keusch</h2>
            <a href="https://github.com/sklf81/webview"> github.com/sklf81/webview </a>
    </div>
    </body>
</html>