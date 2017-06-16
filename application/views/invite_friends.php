<script src="http://connect.facebook.net/en_US/all.js">
   </script>
<script>
     FB.init({ 
       appId:'1636263123279337', cookie:true, 
       status:true, xfbml:true 
     });

     

function FacebookInviteFriends()
{
FB.ui({ method: 'apprequests', 
   message: 'My diaolog...'});
}

FacebookInviteFriends();
   </script>

<div id="fb-root"></div>