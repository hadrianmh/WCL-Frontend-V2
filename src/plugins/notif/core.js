function load_unseen_notification(){
  $('.looping-notif').remove();
  $.ajax({
    url:"../plugins/notif/core.php?action=check",
    method:"POST",
    dataType:"json",
    success:function(output){
      $('.jmlNotif').text(output.data[0].count);
      $('.headerjmlNotif').text('Anda memiliki '+output.data[0].count+' notifikasi');
      for(var i = 0; i<output.data[0].item.length; i++){
        if(output.data[0].item.length > 0){
          $('.kontenjmlNtotif').append(output.data[0].item[i]);  
        } else {
          $('.kontenjmlNtotif').append('<li class="looping-notif"></li>');  
        }
      }
    }
  });
}