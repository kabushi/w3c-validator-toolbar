

var b = function( $ , element) {
	$.fn.validateHtml = function() {
		var generatedSource = new XMLSerializer().serializeToString(document);
		//console.log(generatedSource);
		
		$.ajax({
			url: "http://validator.w3.org/check",
			type : 'POST',
			
			data: {
				output: "json",
				fragment: generatedSource
			}
		});
		
		//document.all[0].outerHTML
	};
	
	$( element ).on("click", function() {
		$( element ).validateHtml();
	});
};


if(!window.jQuery){
	var c=document.createElement("script");
	c.setAttribute("type","text/javascript");
	c.setAttribute("charset","UTF-8");
	c.setAttribute("src","http://code.jquery.com/jquery-1.11.2.min.js");
	document.documentElement.appendChild(c);
	c.onload=c.onreadystatechange=function(){
		var a=c.readyState;
		if(!a||a==="loaded"||a==="complete"){
			c.onload=c.onreadystatechange=null;
			b.call(this, window.jQuery, ".validator-element");
		}
	}
} else {
	b.call(this, window.jQuery,".validator-element");
}





