<?php 
    global $path; 
    $version = 1;
?>   
     
<link rel="stylesheet" href="<?php echo $path; ?>Modules/fastdsr/style.css">


<div id="wrapper">
  <div class="sidenav">
    <div class="sidenav-inner">
      
      <ul class="sidenav-menu">
          <!--
          <li><a href="<?php echo $path; ?>example/view/1">Menu Item 1</a></li>
          <li><a href="<?php echo $path; ?>example/view/2">Menu Item 2</a></li>
          <li><a href="<?php echo $path; ?>example/view/3">Menu Item 3</a></li>
          <li><a href="<?php echo $path; ?>example/view/new"><i class="icon-plus icon-white"></i> New</a></li>-->
      </ul>
      
    </div>
  </div>

  <div style="height:10px"></div>

  <div style="padding:20px">

    <h2>FastDSR</h2>
    
    <table class="table">
        <tr><td width=60%>Current DSR Signal:</td><td><?php echo $fastDSR_status; ?></td></tr>
    </table>
    
    <h4>Proof of Dispatch</h4>
    <table class="table">
        <tr>
            <td width=60%>Last confirmation:</td>
            <td><?php if (isset($lastupdate->signal)) echo $lastupdate->signal; ?></td>
            <td><?php if (isset($lastupdate->time)) echo date("F j, Y, g:i a",(int)$lastupdate->time); ?></td>
        </tr>
    </table>
    
    <div style="height:450px"></div>
    
    <h4>FastDSR API Reference</h4>
    <table class="table">
    <tr>
        <td>GET</td>
        <td><a href="<?php echo $path; ?>fastdsr/signal"><?php echo $path; ?>fastdsr/signal</a></td>
        <td>Return current DSR signal</td>
    </tr>
    <tr>
        <td>GET / POST</td>
        <td><a href="<?php echo $path; ?>fastdsr/confirm-dispatch"><?php echo $path; ?>fastdsr/confirm-dispatch</a></td>
        <td>Confirm that DSR signal has been acted upon</td>
    </tr>
    </table>

  </div>
</div>

<script type="text/javascript" src="<?php echo $path; ?>Modules/fastdsr/sidebar.js?v=<?php echo $version; ?>"></script>

<script>

var path = "<?php echo $path; ?>";

</script>
