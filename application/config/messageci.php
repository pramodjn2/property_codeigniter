<?php

# Message divs will be tagged with your css class.
# Example: Twitter Bootstrap success message:
# $config['message_success_css'] = array('alert', 'alert-success');
$config['message_success_css'] = array('alert', 'alert-success');

# Message divs will be tagged with your css class.
# Example: Twitter Bootstrap error message:
# $config['message_error_css'] = array('alert', 'alert-error');
$config['message_error_css'] = array('alert', 'alert-danger');

# Message divs will be tagged with your css class.
# Example: Twitter Bootstrap info message:
# $config['message_info_css'] = array('alert', 'alert-info');
$config['message_info_css'] = array('alert', 'alert-info');

$config['message_success_icon'] = array('<i class="fa fa-check-circle"></i>');
$config['message_error_icon'] = array('<i class="fa fa-check-circle"></i>');
$config['message_info_icon'] = array('<i class="fa fa-exclamation-triangle"></i>');
$config['message_close_button'] = array('<button data-dismiss="alert" class="close">×</button>');

# All messages will be wrapped in a div. Do you want your text to be wrapped in another element inside the div?
# Example wrap message text in a <p> tag:
# $config['message_inner_wrapper'] = '<p>';
$config['message_inner_wrapper'] = '';