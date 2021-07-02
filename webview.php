<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="style.css"/>
        <title>Wiew: <?php

            $default_dir = "C:\Users\phlpk\Documents\GitHub\webview";
            $debug = false;

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
                foreach($dir_content as $entry){
                    if(!is_dir($dir."/".$entry)){
                        $files[] = $dir."/".$entry;
                    }
                }
                return $files;
            }

            function getDirNames($dir, $dir_content){
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
                                <img src='dir_icon.png' style='width: 20px; height: 20px'></img>
                                <button type='submit' class='dir_button' name='directory' value='".$directory."' class='directorie_link'>".$tmp."</button>
                            </form> 
                        </div>
                    </div>
                    ";
                    echo($html_directory_str);
                }
            }

            function getFileType($file){
                $endings_img = array(".png", ".jpg", ".bmp",".gif",".tif");
                $endings_video = array(".mp4", ".avi");
                $endings_music = array(".mp3", ".flak");
                if(contains($file, $endings_img)){
                    return "img";
                }
                else if(contains($file, $endings_video)){
                    return "video";
                }
                else if(contains($file, $endings_music)){
                    return "music";
                }
                else return "misc";
            }

            function contains($str, $probe_arr){
                foreach($probe_arr as $probe){
                    if(strpos($file, $probe) != 0){
                        return true;
                    }
                }
                return false;
            }

            function echoFiles($default, $files){
                if(!empty($files)){
                    foreach($files as $file){
                        $filetype = getFileType($file);
                        $tmp = removeParentDir($file);
                        $link = str_replace($default, "", $file);
                        $html_file_str = 
                        "
                        <div class='entry' name='file_".$file."'>
                            <div class='file_entry'>
                                <img src='file_".$filetype."_icon.png' style='width: 20px; height: 20px'></img>
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
                    if(strpos($cur_dir, "/..") != 0){
                        $dir = revertDirectory($cur_dir);
                        if(strpos($dir, $default_dir) === false){
                            return $default_dir;
                        }
                        else{
                            return $dir;
                        }
                    }
                    else if(strpos($cur_dir, "/.")){
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

            if($debug){
                printf("ALL: \n");
                print_r($dir_content);
                printf("DIR: \n");
                print_r($directories);
                printf("FILES: \n");
                print_r($files);
            }

            echoDirs($directories);
            echoFiles($default_dir, $files);

        ?>


    </body>
</html>