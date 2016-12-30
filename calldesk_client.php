
<?php
$colours = array('007AFF','FF7000','FF7000','15E25F','CFC700','CFC700','CF1100','CF00BE','F00');
$user_colour = array_rand($colours);




?>


<script src="jquery-3.1.1.js"></script>



<script language="javascript" type="text/javascript">

    // Global  variable
    //create a new WebSocket object.
   var wsUri = "ws://192.168.1.4:9000/demo/server.php";
   var websocket = new WebSocket(wsUri);
   var authorname = [];
   var emails = [];

$(document).ready(function(){


        

        websocket.onopen = function(ev) { // connection is open
                
                        $('#state').html("<b>Диспетчер звонков:</b> Готов к работе"); //notify user

                        var myextnumber = "<? echo $extnumber ?>"; //get extnumber
                        var msg_type='onopen';
                        var msg1 = {
                            msg_type: msg_type,
                            extnumber: myextnumber
                        }
                          //convert and send data to server
                         websocket.send(JSON.stringify(msg1));
        }

        websocket.onclose = function (ev) {
            $('#state').html("<b>Диспетчер звонков:</b> Нет связи ! <input type=\"button\" onClick=\"history.go(0)\" value=\"Try again\">"); //notify user

        };

 

         $('#send-btn').click(function(){ //use clicks message send button
                var clientname = $('#clientname').val(); //get message text
                var taskname = $('#taskname').val(); //get user name

                if( clientname  == ""){ //empty name?
                        alert("Enter Client Name please!");
                        return;
                }
                if(taskname == ""){ //emtpy taskname?
                        alert("Enter Some TastName Please!");
                        return;
                }


                //document.getElementById("name").style.visibility = "hidden";


                //var objDiv = document.getElementById("message_box");
                //objDiv.scrollTop = objDiv.scrollHeight;
                //prepare json data
                var msg_type = 'forDBinsert';
                var msg = {
                msg_type: msg_type,
                extnumber: '<?php echo $extnumber; ?>',
                clientname: clientname
                };

 alert ("msg:"+clientname);
                //alert($.toJSON(msg));
                //convert and send data to server
                //websocket.send(JSON.stringify(msg));
        });



        //#### Message received from server?
        websocket.onmessage = function(ev) {
                var msg = JSON.parse(ev.data); //PHP sends Json data
                var type = msg.type; //message type
                var umsg = msg.message; //message text
                var uname = msg.name; //user name
                var ucolor = msg.color; //color
                var callnumer = msg.callnumer; //call number
                var calltype = msg.calltype; // outgoing or incoming
                var datetime = msg.datetime; // time of answer of call
                var id_call = msg.id_call; // Mysql DB call_id
                var id = msg.id; // callid
                var id_taskauthor = msg.id_taskauthor; // get from phone number
                var author_name = msg.author_name; // get from id_taskauthor
                var email =  msg.email; // get from id_taskauthor
                var bgcolor = msg.bgcolor; // get background color for div
                
                old_name=author_name;
                old_email=email;
                
                authorname[id_call]=author_name;
                emails[id_call]=email;
                
                

                 if(calltype=='OutCall')
                 {
                   calltype1='Исходящий';
                 }
                 else if(calltype=='InCall')
                 {
                    calltype1='Входящий';
                 }
                   
   
                if(type == 'call')
                {
                   
                     var div1 = document.createElement('div');

        
                     cont1 = "<div id=\""+id_call+"\" style=\"background-color: "+bgcolor+"\"><div><b>Вызов:</b>"+calltype1+
                             "</div><div><b>Номер:</b>"+callnumer+
                             "</div><div><b>Дата:</b>"+datetime+"</div>";
                       
                      if(id_taskauthor === null)
                      {   
                       cont2 = "<div><b>Имя абонента:</b><input type=\"text\" id=\"clientname"+id_call+"\" name=\"clientname\" size=\"80\" maxlength></div>";                              
                      }
                      else
                      {
                       
                       cont2 = "<div id=\"name"+id_call+"\"><b>Имя абонента:</b>"+author_name+"<button onclick=\"changeName("+id_call+")\">Изменить</button></div><dev id=\"name2"+id_call+"\"></dev>"+
                                      "<input type=\"hidden\" id=\"clientname"+id_call+"\" name=\"clientname\" value=\""+author_name+"\">";
                      }
                      
                      if(email === null)
                      {   
                       cont3 = "<div><b>E-mail:</b><input type=\"text\" id=\"email"+id_call+"\" name=\"email\" size=\"80\" maxlength></div>";                              
                      }
                      else
                      {                       
                       cont3 = "<div id=\"email1"+id_call+"\"><b>E-mail:</b>"+email+"<button onclick=\"changeEmail("+id_call+")\">Изменить</button></div><dev id=\"email2"+id_call+"\"></dev>"+
                                      "<input type=\"hidden\" id=\"email"+id_call+"\" name=\"email\" value=\""+email+"\">";
                      }
                             
                      cont4 = "<div><b>Краткое описание:</b><input id=\"taskname"+id_call+"\" type=\"text\" name=\"taskname\" size=\"80\" maxlength>"+
                             "</div><div><b>Комментарий:</b><textarea id=\"comment"+id_call+"\" rows=\"10\" cols=\"80\" name=\"comment\"></textarea>"+
                             "<input id=\"id_call"+id_call+"\" type=\"hidden\" name=\"id_call\" value=\""+id_call+"\">"+                             
                             "<input id=\"calltype"+id_call+"\" type=\"hidden\" name=\"calltype\" value=\""+calltype+"\">"+
                             "<input id=\"callnumber"+id_call+"\" type=\"hidden\" name=\"callnumer\" value=\""+callnumer+"\">"+
                             "<input id=\"datetime"+id_call+"\" type=\"hidden\" name=\"datetime\" value=\""+datetime+"\">"+
                             "<input id=\"id_taskauthor"+id_call+"\" type=\"hidden\" name=\"id_taskauthor\" value=\""+id_taskauthor+"\">"+
                             "<input id=\"old_name"+id_call+"\" type=\"hidden\" name=\"old_name\" value=\""+old_name+"\">"+
                             "<input id=\"old_email"+id_call+"\" type=\"hidden\" name=\"old_email\" value=\""+old_email+"\"></div>"+
                             "<div><button name=sendtodb onclick=\"activateTask("+id_call+")\">Активировать заявку</button></div>"+
                             "<div><button onclick=\"closeTask()\">Закрыть как выполненая</button></div>"+
                             "<div><button onclick=\"removeCall("+id_call+")\">Сбросить</button></div>"+                                                         
                             "<hr>"+
                             "</div>";
                      
                      
                      cont=cont1+cont2+cont3+cont4;
                      
                     div1.innerHTML = cont;
                             
                        //document.body.insertBefore(div1, document.body.firstChild);
                     call.appendChild(div1);
                 
                }


                if(type == 'system')
                {
                  $('#state').html("<b>Диспетчер звонков:</b>"+umsg); //notify user
                }

       

        //      var objDiv = document.getElementById("message_box");
        //      objDiv.scrollTop = objDiv.scrollHeight;
        };

        //websocket.onerror     = function(ev){$('#message_box').append("<div class=\"system_error\">Error Occurred - "+ev.data+"</div>");};
        //websocket.onclose     = function(ev){$('#message_box').append("<div class=\"system_msg\">Connection Closed</div>");};
});


    

function changeName(id_call) {
    
     var elem = document.getElementById("name"+id_call);
     elem.parentNode.removeChild(elem);
     var elem2 = document.getElementById("name2"+id_call);
     var author_name=authorname[id_call];
     
    
     
     
     $('#'+'name2'+id_call).append('<div><b>Имя абонента:</b><input type="text" id=\"clientname'+id_call+'\" value="'+authorname[id_call]+'" name="clientname" size="80" maxlength></div>');
     //return false;
}

function changeEmail(id_call) {
     //alert (" Name:"+authorname[id_call]);
     var elem = document.getElementById("email1"+id_call);
     elem.parentNode.removeChild(elem);
     var elem2 = document.getElementById("email2"+id_call);
     var email=emails[id_call];
          
     $('#'+'email2'+id_call).append('<div><b>E-mail:</b><input type="text" id=\"email'+id_call+'\" value="'+email+'" name="email" size="80" maxlength></div>');
     //return false;
}

function removeCall(id_call) {
     var id_call = document.getElementById("id_call"+id_call).value;
     var msg = {
            msg_type: 'canselTask',
            id_call: id_call
        }
     
     websocket.send(JSON.stringify(msg));
     
     function sleep(milliseconds) {
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds){
            break;
            }
        }
    }

     sleep(1000);
     
     var elem = document.getElementById(id_call);
     elem.parentNode.removeChild(elem);
     return false;
}

function activateTask(id_call) {
      
    var id_call = document.getElementById("id_call"+id_call).value;
    var id_taskauthor = document.getElementById("id_taskauthor"+id_call).value;
    var datetime = document.getElementById("datetime"+id_call).value;    
    var clientname = document.getElementById("clientname"+id_call).value;
    var callnumber = document.getElementById("callnumber"+id_call).value;
    var taskname = document.getElementById("taskname"+id_call).value;
    var comment = document.getElementById("comment"+id_call).value;            
    var old_name = document.getElementById("old_name"+id_call).value;
    var email = document.getElementById("email"+id_call).value;      
    var old_email = document.getElementById("old_email"+id_call).value;
                  
      
        if(clientname == '')
        {    
         alert("Не задано имя абонента!");
          return;
        } 
        
        if(taskname == '')
        {    
         alert("Не задано краткое описание!");
          return;
        } 
        
    
    
    var msg = {
                            msg_type: 'saveTask',
                            id_taskauthor: id_taskauthor,
                            id_call: id_call,
                            datetime: datetime,               
                            clientname: clientname,
                            callnumber: callnumber,
                            old_name: old_name,
                            email: email,
                            old_email: old_email,
                            taskname: taskname,
                            comment: comment

              }
    
       
     websocket.send(JSON.stringify(msg));
     
     function sleep(milliseconds) {
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds){
            break;
            }
        }
    }

     sleep(1000);
     
     
     //setTimeout(alert("Save task"),300);
     
     var elem = document.getElementById(id_call);
     elem.parentNode.removeChild(elem);
     return false;
     
}



</script>

<?php
$content.='<div id="state"><b>Диспетчер звонков:</b></div>';
$content.='<center><div id="call"></div></center>';
?>
                

