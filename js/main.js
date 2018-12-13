/* Cloudflare CNAME Setup Main JS */
$(function () {
	$('[data-toggle="tooltip"]').tooltip();
});

/* Add Record */

var content = document.getElementById("dns-content");

var caa = document.getElementById("dns-data-caa");
if(caa && content){
	caa.style.display = "none";

	document.getElementById("type").addEventListener('change', function () {
		if(this.value === "CAA"){
			caa.style.display = "block";
			content.style.display = "none";
		} else {
			caa.style.display = "none";
			content.style.display = "block";
		}
	})
}

var mxPriority = document.getElementById("dns-mx-priority");

if(mxPriority && content){
	mxPriority.style.display = "none";

	document.getElementById("type").addEventListener('change', function () {
		if(this.value === "MX"){
			mxPriority.style.display = "block";
		} else {
			mxPriority.style.display = "none";
		}
	})
}

var srv = document.getElementById("dns-data-srv");

if(srv && content){
	srv.style.display = "none";

	document.getElementById("type").addEventListener('change', function () {
		if(this.value === "SRV"){
			srv.style.display = "block";
			content.style.display = "none";
		} else {
			srv.style.display = "none";
			content.style.display = "block";
		}
	})
}

/* Implement "data-selected" feature */

var selects = document.getElementsByTagName("select");

if(selects){
	for(var j=0; j<selects.length; j++){
		var selected = selects[j].getAttribute("data-selected");
		if(!selected){
			continue;
		}
		var options = selects[j].getElementsByTagName("option");
		for (var i = 0; i<options.length; i++){
			if(options[i].getAttribute("value") === selected){
				options[i].setAttribute("selected", "selected");
				break;
			}
		}
	}
}
