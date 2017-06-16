<?php
$message = $this->messageci->display();
if(isset($message) && count($message)>0){
?>
<!-- start: Modal for registration and login  --> 
  <div class="modal fade" id="notification_alert" role="dialog" style="display:none;">
    <div class="modal-dialog">
      <!-- start: Modal content-->
      <div class="modal-content">
        <!--<div class="modal-header noborder">
          GET CONNECTED WITH OTRIGA-PORTAL
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>-->
        <div class="modal-body">			           
            <?php for($i=0; $i<count($message); $i++)echo $message[$i];?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- end: Modal content-->
    </div>
  </div>  
<!-- end: Modal for registration and login  -->
<script> 
$(document).ready(function(e) {
    $('#notification_alert').modal('show');
});
</script>
<?php }?>
 