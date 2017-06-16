<?php $this->load->view('common/common_modals'); ?>

<?php $this->load->view('common/notification_alert');
$current_url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if(@$_GET['login'] != 'signin'){
$this->session->set_userdata('request_url', $current_url);
}
?>

<style>

.grid-content-detail{

 height: 65px;

 overflow-x: hidden;

 overflow-y: hidden;

}

</style>

</body>

<!-- end: BODY -->

</html>