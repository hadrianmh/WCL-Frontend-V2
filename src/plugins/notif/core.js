function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
          c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
      }
  }
  return "";
}

function load_unseen_notification()
{
  var endpoint  = decodeURIComponent(getCookie('base_url_api')) +':'+ getCookie('base_port_api') + decodeURIComponent(getCookie('base_path_api')) + decodeURIComponent(getCookie('base_dashboard_api'));
  $('.looping-notif').remove();

  $.ajax({
    url: endpoint + '/metrics/notification',
		type: "GET",
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', getCookie('access_token'));
			xhr.setRequestHeader('Content-Type', 'application/json');
		},
    success:function(output)
    {
      $('.jmlNotif').text(output.response.data[0].total_counter);
      $('.headerjmlNotif').text('Anda memiliki '+output.response.data[0].total_counter+' notifikasi');

      if(output.response.data[0].account === 1)
      {
        let html = '';
        if(output.response.data[0].role === 1 || output.response.data[0].role === 5)
        {
          html += "<li class='looping-notif'><a href='index.php?page=invoice_waiting'><i class='fa fa-sticky-note-o text-yellow'></i>"+output.response.data[0].inv_waiting_counter+" Faktur baru</a></li>";
          html += "<li class='looping-notif'><a href='index.php?page=invoice_duedate'><i class='fa fa-sticky-note-o text-yellow'></i>"+output.response.data[0].inv_duedate_counter+" Faktur jatuh tempo</a></li>";
          html += "<li class='looping-notif'><a href='index.php?page=workorder'><i class='fa fa-send-o text-aqua'></i>"+output.response.data[0].wo_waiting_counter+" SPK baru</a></li>";
          html += "<li class='looping-notif'><a href='index.php?page=delivery_orders_waiting'><i class='fa fa-clock-o text-red'></i>"+output.response.data[0].do_waiting_counter+" SPK masuk tenggat waktu</a></li>";
          html += "<li class='looping-notif'><a href='index.php?page=delivery_orders_waiting'><i class='fa fa-truck text-green'></i>"+output.response.data[0].do_duedate_counter+" Surat jalan belum diproses</a></li>";

          $('.kontenjmlNtotif').append(html);

        } else if(output.response.data[0].role === 2) {
          html += "<li class='looping-notif'><a href='index.php?page=workorder'><i class='fa fa-send-o text-aqua'></i>"+output.response.data[0].wo_waiting_counter+" SPK baru</a></li>";
          html += "<li class='looping-notif'><a href='index.php?page=delivery_orders_waiting'><i class='fa fa-clock-o text-red'></i>"+output.response.data[0].do_waiting_counter+" SPK masuk tenggat waktu</a></li>";
          html += "<li class='looping-notif'><a href='index.php?page=delivery_orders_waiting'><i class='fa fa-truck text-green'></i>"+output.response.data[0].do_duedate_counter+" Surat jalan belum diproses</a></li>";

          $('.kontenjmlNtotif').append(html);

        } else if(output.response.data[0].role === 4) {
          html += "<li class='looping-notif'><a href='index.php?page=invoice_waiting'><i class='fa fa-sticky-note-o text-yellow'></i>"+output.response.data[0].inv_waiting_counter+" Faktur baru</a></li>";
          html += "<li class='looping-notif'><a href='index.php?page=invoice_duedate'><i class='fa fa-sticky-note-o text-yellow'></i>"+output.response.data[0].inv_duedate_counter+" Faktur jatuh tempo</a></li>";

          $('.kontenjmlNtotif').append(html);

        } else {
          $('.kontenjmlNtotif').append('<li class="looping-notif"></li>');
        }
      }
    }
  });
}