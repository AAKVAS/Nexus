let sendMessageButton  = document.getElementById("send_message_button");
sendMessageButton.onclick = function (){
    let messageText = document.getElementById("message_text").innerText;
    let partnerId = document.getElementById("partner_info").id;
    $.ajax({
       url: 'scripts/sendMessage.php',
       method: 'post',
       dataType: 'html',
       data: {
           text: messageText,
           partner_id: partnerId
       },
        success: function (data){
            alert(data);
        }
    });
}

