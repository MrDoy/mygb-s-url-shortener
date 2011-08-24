	<script type="text/javascript">
            function shorturl(){
                //alert(document.getElementById('inputurl').value);
                var xhr = new XMLHttpRequest();
                if(window.XMLHttpRequest || window.ActiveXObject) {
                    if(window.ActiveXObject) {
                        try{
                            xhr = new ActiveXObject("Msxml2.XMLHTTP");
                        }catch(e){
                            xhr = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                    }else{
                        xhr = new XMLHttpRequest(); 
                    }
                    
                    document.getElementById('inputbutton').type="button";
                    
                    xhr.open("GET", "action.php?key="+document.getElementById('inputkey').value+"&client="+document.getElementById('inputclient').value+"&url="+document.getElementById('inputurl').value+"&shortid="+document.getElementById('shortid').value, true);
                    xhr.send(null);
                    
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
                            document.getElementById('inputurl').value=xhr.responseText;
                        }
                    };
                }
            }
            
            function enlarge(){
            	if(document.getElementById('shortiddiv').style.display=='block'){
					document.getElementById('shortiddiv').style.display='none'
				}else{
					document.getElementById('shortiddiv').style.display='block'	
				}
            }
        
            //document.getElementById('javascriptbutton').innerHTML='<button onClick="shorturl()" type="button" id="inputbutton">Short this !</button>';
        </script>
        
        <section id="logo">
        	<img src="title.png" alt="Header - title"/>
        </section>
    	<div id="styleform">
    	<form action="sendnojs.html" method="post" name="sendurl">
        	<input type="text" name="url" id="inputurl" value="http://<?php if(!empty($_GET['url'])){ echo htmlentities($_GET['url']).'"'; }?>" /><span id="javascriptbutton"></span>
            <input type="hidden" name="key" id="inputkey" value="web"/>
            <input type="hidden" name="client" id="inputclient" value="web"/>
            <button type="submit" nameform="sendurl" onClick="shorturl()" id="inputbutton">Short this !</button>
			<?php
            if($config['params']['allowcustomshortid']){
				?>
			<button type="button" id="expand" onclick="enlarge()">+</button>
            
            <div id="shortiddiv" style="display:none;">
            <input type="text" name="shortid" id="shortid" value="<?php echo $locale['index'][5];?>"/>
            </div>
            <?php
			}?>
        </form>
        </div>
