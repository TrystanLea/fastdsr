<?php

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function fastdsr_controller() {

    global $session, $route, $mysqli;
    
    // Default route format
    $route->format = 'json';
    
    // $fastDSR_status = "ELEMENTS_ON";  // Not actionable?
    $signals = array("NONE","ELEMENTS_OFF");
    $fastDSR_status = $signals[0];
    
    // Result can be passed back at the end or part way in the controller
    $result = false;
    
    require "Modules/fastdsr/fastdsr_model.php";
    $fastdsr = new FastDSR($mysqli);
    
    // Write access API's and pages
    if ($session['write']) {
    
        if ($route->action == "signal") {
            $route->format = 'json';
            return array("signal"=>$fastDSR_status);
        }
        
        if ($route->action == "confirm-dispatch" && isset($_GET['signal'])) {
            $route->format = 'json';
            
            $userid = $session["userid"];
            $time = time();
            $signal = prop('signal');
            
            if (in_array($signal,$signals)) {
            
                $mysqli->query("DELETE FROM fastdsr WHERE `userid`='$userid'");
                $stmt = $mysqli->prepare("INSERT INTO fastdsr (`userid`,`time`,`signal`) VALUES (?,?,?)");
                $stmt->bind_param("iis",$userid,$time,$signal);
                if (!$result = $stmt->execute()) $error = $stmt->error;
                $stmt->close();
                
                if ($result) {
                    return array("success"=>true);
                } else {
                    return array("success"=>false,"message"=>$error);
                }
            } else {
                return array("success"=>false, "message"=>"invalid signal");
            }
        }
    
        if ($route->action == "view") {
            $route->format = 'html';
            
            $userid = $session["userid"];
            $result = $mysqli->query("SELECT `time`,`signal` FROM fastdsr WHERE `userid`='$userid'");
            $row = $result->fetch_object();
            return view("Modules/fastdsr/fastdsr_view.php", array("fastDSR_status"=>$fastDSR_status,"lastupdate"=>$row));
        }
        
    // Read access API's and pages
    } else if ($session['read']) {
    
    // Public API's
    } else {
        if ($route->action == "signal") {
            $route->format = 'json';
            return array("signal"=>$fastDSR_status);
        }
    }
    

    // Pass back result
    return array('content' => $result);
}
