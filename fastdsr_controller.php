<?php

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function fastdsr_controller() {

    global $session, $route, $mysqli;
    
    // Default route format
    $route->format = 'json';
    
    // Set DSR Status
    // NONE:          no DSR action required
    // ELEMENTS_OFF:  turn off storage heater elements
    // ELEMENTS_ON:   turn on storage heater elements (not actionable?)
    $signals = array("NONE","ELEMENTS_OFF","ELEMENTS_ON");
    $fastDSR_status = $signals[0];
    
    // Result can be passed back at the end or part way in the controller
    $result = false;
    
    // require "Modules/fastdsr/fastdsr_model.php";
    // $fastdsr = new FastDSR($mysqli);
    
    // Write access API's and pages
    if ($session['write']) {
    
        // Signal API:  /emoncms/fastdsr/signal
        // result e.g:  {"signal":"NONE"}
        if ($route->action == "signal") {
            $route->format = 'json';
            return array("signal"=>$fastDSR_status);
        }
        
        // Proof of dispatch API: /emoncms/fastdsr/confirm-dispatch?signal=NONE
        // result: {"success":true} or error message
        if ($route->action == "confirm-dispatch" && isset($_GET['signal'])) {
            $route->format = 'json';
            
            $userid = $session["userid"];
            $time = time();
            $signal = prop('signal');
            
            // Verify that returned signal is a valid signal
            if (in_array($signal,$signals)) {
                // Insert or update entry in mysql database
                // userid, time received and signal actioned
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
    
        // Load fastdsr html user interface
        // Load current DSR signal and users last proof of dispatch
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
