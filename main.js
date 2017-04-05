function tabpick(num){
	$('#myTabs li:eq('.concat(num.toString(),') a')).tab('show');
}

function clear(){
	document.getElementById("keyword").value="";
}