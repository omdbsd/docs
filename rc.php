<?php
    error_reporting(E_ALL || ~E_NOTICE);

    if(isset($_POST['rcm3u8'])) {
        $surl = trim($_POST['surl']);        
        $vname = trim($_POST['vname']);
        $duration = trim($_POST['duration']);
        
        if(empty($duration)) {
            $command1 = "hlsdl -b -f -o /var/www/hub/" . $vname . ".ts "  . " '" . $surl . "' &" ;
        }
        else {
            $command1 = "hlsdl -b -f -o /var/www/hub/" . $vname . ".ts -i " . $duration . " '" . $surl . "' &" ;
        }        
        $str1 = shell_exec($command1);        
        
        $command2 = "ffmpeg -y -i /var/www/hub/" . $vname . ".ts -c copy -movflags faststart /var/www/hub/" . $vname . ".mp4 </dev/null > /dev/null 2>&1 &";
        $str2 = shell_exec($command2);
    }


    if(isset($_POST['rcother'])) {
        $surl = trim($_POST['surl']);
        $vname = trim($_POST['vname']);
        $duration = trim($_POST['duration']); 
        
        $command3 = "ffmpeg -y -i '" . $surl . "' -c copy -t " . $duration . " /var/www/hub/" . $vname . ".ts </dev/null > /dev/null 2>&1";
        $str3 = shell_exec($command3);
        
        $command4 = "ffmpeg -y -i /var/www/hub/" . $vname . ".ts -c copy -movflags faststart /var/www/hub/" . $vname . ".mp4 </dev/null > /dev/null 2>&1 &";
        $str4 = shell_exec($command4);    
    }
?>

<form action="rc_new.php" method="post">
    <p>stream url: <input type="text" name="surl" /></p>
    <p>video name: <input type="text" name="vname" /></p>
    <p>duration (in seconds): <input type="text" name="duration" /></p>
    <p><input type="submit" name="rcm3u8" value="record m3u8 stream"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="rcother" value="record any stream"/></p>
</form>
