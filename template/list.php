<?php
function reduceUrl($url){
	if(strlen($url)>40){
			$splited = str_split($url,30);
			$final = $splited[0].' [...] '.$splited[(count($splited)-1)];
	}else{
			$final = $url;
	}
	return addslashes($final);
}

?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type='text/javascript'>
	google.load('visualization', '1', {packages:['table']});
	google.setOnLoadCallback(drawTable);
	function drawTable() {
		var data = new google.visualization.DataTable();
		var table = new google.visualization.Table(document.getElementById('shortlist'));
		
	<?php
	if(empty($_GET['id'])){	
	?>
		data.addColumn('string', 'Host');
		data.addColumn('string', 'Short id');
		data.addColumn('string', 'URL');
		data.addColumn('date', 'Date');
		data.addColumn('number', 'Distinct clics');
		data.addColumn('number', 'Total clics');
		<?php
		
		foreach($shortlist as $value){
			echo 'data.addRow([
				"'.$value['host'].'",
			"<a id=\''.$value['shortid'].'\' onClick=loadStats(\''.$value['shortid'].'\'); href=\''.$config['params']['protocol'].'://'.$value['host'].'/'.$value['shortid'].$config['params']['infochar'].'\'>'.$value['shortid'].'</a>",
				"<a href=\''.$value['url'].'\'>'.reduceUrl($value['url']).'</a>",
				new Date("'.date('d, M Y G:h:s',$value['date']).'"),
				'.$value['countdistinct'].',
				'.$value['counttotal'].']);
				';
			}
				
		}else{
		?>
		data.addColumn('date', 'Date clicked');
		data.addColumn('string', 'IP');
		data.addColumn('string', 'User-agent');
		data.addColumn('string', 'Referer');
		<?php
		
		if($shortlist[0]['dateclic']!=NULL){
			foreach($shortlist as $value){
				echo 'data.addRow([
					new Date("'.date('d, M Y G:h:s',$value['dateclic']).'"),
					"'.$value['ip'].'",
					"'.$value['useragent'].'",
					"'.$value['referer'].'"]);
					';
			}
		}else{
			echo 'data.addRow();';
		}
	}
	?>
			table.draw(data, {showRowNumber: false,allowHtml: true,pageSize:15,sortAscending:false,sortColumn:3,page:'enable'});
		}
</script>

<div id='shortlist'></div>
<div id='infos'></div>

<script type="text/javascript">
	function loadStats(shortid){
		/*alert(shortid);
		document.getElementById(shortid).href="#";
		
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
			
			xhr.open("GET", shortid+"++", true);
			xhr.send(null);
			
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					document.getElementById('infos').innerHTML=xhr.responseText;
					malerter();
				}
			};
		}*/
		
		
	}
</script>