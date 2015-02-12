
var req;
function checkEmpty() {
	if(document.getElementById("title").value == "") {
		alert("Please enter something in the search box"); 
		return false;
	}
	else {
		return true;
	}
}


//Get JSON result from sevlet. 
function Callback() {
	if (req.readyState==4) {// only if "loaded"
		if (req.status==200) {// only if "OK"
			var doc = eval('(' + req.responseText + ')');
			if(doc.results.result.length == 0) {
				document.getElementById("table").innerHTML =  '<p>No results found</p>';
			}
			else {
				DisplayTable(doc);
			}
		}
		else {
			alert("There was a problem retrieving the XML data:\n" + req.statusText);
		}
	}
}


// Display Table : the result of  music information
function DisplayTable(doc) {
	var result = "<p style=\"font-size:35px;font-weight:900\">Search Result</p>";
	result += "<p style=\"font-size:20px;font-weight:600\">";
	result += document.getElementById("title").value + " of type \"" + document.getElementById("type").value + "\"";
	if(document.getElementById("type").value == "artists") {
		result += "</p><table border='2'><tr><th>Cover</th><th width=200>Name</th><th width=200>Genre(s)</th>";
		result += "<th width=200>Year(s)</th><th width=200>Detail</th><th>Post To Facebook</th></tr>";
		var count = 0;
		for(var item in doc.results.result) {
			item = doc.results.result[count];
			result += "<tr>"; 					
			result += "<td align='center'><img src=\"" + item.cover +"\"></td>"; 
			result += "<td align='center'>"+ item.name +"</td>";   
			result += "<td align='center'>"+ item.genre +"</td>"; 
			result += "<td align='center'>"+ item.year +"</td>"; 
			result += "<td align='center'>"+ "<a href=\"" + item.detail+ "\">details</a>" +"</td>"; 
			result += "<td align='center'><img src=\"facebook.jpg\" width='85' height='50' onclick=\"login1(";		
			result += "'" + item.cover + "'" + ",";
			result += "'" + item.name + "'" + ",";
			result += "'" + item.year + "'" + ",";
			result += "'" + item.genre + "'" + ",";
			result += "'" + item.detail + "'" + ")\"></td></tr>";
			count ++;
		}
	}
	else if(document.getElementById("type").value == "albums") {
		result += "</p><table border='2'><tr><th>Cover</th><th width=200>Title</th><th width=200>Artist(s)</th>";
		result += "<th width=200>Genre(s)</th><th width=200>Year</th><th width=200>Detail</th><th>Post To Facebook</th></tr>";
		var count = 0;
		for(var item in doc.results.result) {
			item = doc.results.result[count];
			result += "<tr>"; 					
			result += "<td align='center'><img src=\"" + item.cover +"\"></td>"; 
			result += "<td align='center'>"+ item.title +"</td>";   
			result += "<td align='center'>"+ item.artist +"</td>"; 
			result += "<td align='center'>"+ item.genre +"</td>"; 
			result += "<td align='center'>"+ item.year +"</td>";
			result += "<td align='center'>"+ "<a href=\"" + item.detail+ "\">details</a>" +"</td>"; 
			result += "<td align='center'><img src=\"facebook.jpg\" width='85' height='50' onclick=\"login2(";			
			result += "'" + item.cover + "'" + ",";
			result += "'" + item.title + "'" + ",";
			result += "'" + item.artist + "'" + ",";
			result += "'" + item.year + "'" + ",";
			result += "'" + item.genre + "'" + ",";
			result += "'" + item.detail + "'" + ")\"></td></tr>";
			count ++;
		}
	}
	else if(document.getElementById("type").value == "songs") {
		result += "</p><table border='2'><tr><th>Sample</th><th width=200>Title</th><th width=200>Performer(s)</th>";
		result += "<th width=200>Composer(s)</th><th width=200>Detail</th><th>Post To Facebook</th></tr>";
		var count = 0;
		for(var item in doc.results.result) {
			item = doc.results.result[count];
			result += "<tr>"; 
			result += "<td align='center'><a href=\""+ item.sample +"\" target=\"_blank\"><img src=\"headphone.jpg\" /></a></td>";			
			result += "<td align='center'>"+ item.title +"</td>"; 
			result += "<td align='center'>"+ item.performer +"</td>"; 
			result += "<td align='center'>"+ item.composer +"</td>";
			result += "<td align='center'>"+ "<a href=\"" + item.detail+ "\">details</a>" +"</td>"; 
			result += "<td align='center'><img src=\"facebook.jpg\" width='85' height='50' onclick=\"login3(";			
			result += "'" + item.title + "'" + ",";
			result += "'" + item.composer + "'" + ",";
			result += "'" + item.performer + "'" + ",";
			result += "'" + item.detail + "'" + ")\"></td></tr>";
			count ++;
		}
	}
	result += "</table>"; 
				
	document.getElementById("table").innerHTML = result; 
}

function ajax() {
	req = false;
	var url = "http://cs-server.usc.edu:14296/examples/servlet/HelloWorldExample?title="
	url += document.getElementById("title").value.replace(" ", "%2B");
	url += "&type=" + document.getElementById("type").value.replace(" ", "%2B");
	
	if(checkEmpty() == false) {
		return false;
	}
	if(window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	}
	else {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if(req) {
		req.open("GET",url,true);
		req.onreadystatechange=Callback;
		req.setRequestHeader("Connection", "Close");
		req.setRequestHeader("Method", "GET" + url + "HTTP/1.1");	
		req.send();
	}
	else {
		alert("Your browser does not support XMLHTTP.");
	}
}
