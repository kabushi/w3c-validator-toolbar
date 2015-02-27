var initW3c = function( $ , element) {
	$.fn.validateHtml = function() {

		var node = document.doctype;
		var generatedSource = "<!DOCTYPE "
		         + node.name
		         + (node.publicId ? ' PUBLIC "' + node.publicId + '"' : '')
		         + (!node.publicId && node.systemId ? ' SYSTEM' : '') 
		         + (node.systemId ? ' "' + node.systemId + '"' : '')
		         + '>\n';

		var html = $('HTML').clone();
		html.find("#zend-developer-toolbar").remove();
		generatedSource += '<html>'  + html.html()  + '</html>';

		$.ajax({
			url: "**BASEURL**/ajax",
			type : 'POST',
			data: {
				type: 'html',
				fragment: generatedSource
			},
			
			success: function(data) {
				var html = '';
				if(data.status === "Valid") {
					html += '<img src="data:image/png,%89PNG%0D%0A%1A%0A%00%00%00%0DIHDR%00%00%00%10%00%00%00%10%08%02%00%00%00%90%91h6%00%00%00%19IDAT(%91c%0C%DD%10%C5%40%0A%60%22I%F5%A8%86Q%0DCJ%03%00dy%01%7F%0C%9F0%7D%00%00%00%00IEND%AEB%60%82" class="zdt-toolbar-entry" />';
				} else {
					html += '<img src="data:image/png,%89PNG%0D%0A%1A%0A%00%00%00%0DIHDR%00%00%00%10%00%00%00%10%08%02%00%00%00%90%91h6%00%00%00%19IDAT(%91c%BCd%AB%C2%40%0A%60%22I%F5%A8%86Q%0DCJ%03%00%DE%B5%01S%07%88%8FG%00%00%00%00IEND%AEB%60%82" class="zdt-toolbar-entry" />';
				}
				html += '<div style="padding-left: 20px;">' + (data.errors.num > 0 ? data.errors.num + ' errors<br/>' : '') + (data.warnings.num > 0 ? data.warnings.num + ' warnings' : '') + '</div>';
				html += '<form target="_blank" action="http://validator.w3.org/check" method="post" enctype="multipart/form-data"><textarea style="display:none" name="fragment">' + String(generatedSource).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') + '</textarea><button type="submit">Show details</button></form>';
				$(element).find(".html-detail").html(html);
			}
		});
	};
	
	$.fn.validateCss = function() {
		
		var text = '';
		var links = [];
		
		$('style').each(function() {
			text += '\n\n/*------------\n * Style in page\n */\n' + $(this).text();
		});
		
		$('link[rel="stylesheet"]').each(function() {
			href = $(this).attr("href");
			re = /^http:\/\//i;
			
			if(re.test(href)) {
				links.push(href);
			} else {
				var url = href,
					base_url = document.location.href;
					doc      = document,
					old_base = doc.getElementsByTagName('base')[0],
					old_href = old_base && old_base.href,
					doc_head = doc.head || doc.getElementsByTagName('head')[0],
					our_base = old_base || doc_head.appendChild(doc.createElement('base')),
					resolver = doc.createElement('a'),
					resolved_url = href;
				our_base.href = base_url;
				resolver.href = url;
				resolved_url  = resolver.href; // browser magic at work here

				if (old_base) {
					old_base.href = old_href;
				} else {
					doc_head.removeChild(our_base);
				}
				links.push(resolved_url );
			}
		});
		

		$.ajax({
			url: "**BASEURL**/ajax",
			type : 'POST',
			data: {
				type: 'css',
				text: text,
				links: links
			},
			success: function(data) {
				var html = '';
				if(data.status === "Valid") {
					html += '<img src="data:image/png,%89PNG%0D%0A%1A%0A%00%00%00%0DIHDR%00%00%00%10%00%00%00%10%08%02%00%00%00%90%91h6%00%00%00%19IDAT(%91c%0C%DD%10%C5%40%0A%60%22I%F5%A8%86Q%0DCJ%03%00dy%01%7F%0C%9F0%7D%00%00%00%00IEND%AEB%60%82" class="zdt-toolbar-entry" />';
				} else {
					html += '<img src="data:image/png,%89PNG%0D%0A%1A%0A%00%00%00%0DIHDR%00%00%00%10%00%00%00%10%08%02%00%00%00%90%91h6%00%00%00%19IDAT(%91c%BCd%AB%C2%40%0A%60%22I%F5%A8%86Q%0DCJ%03%00%DE%B5%01S%07%88%8FG%00%00%00%00IEND%AEB%60%82" class="zdt-toolbar-entry" />';
				}
				html += '<div style="padding-left: 20px;">' + (data.errors.num > 0 ? data.errors.num + ' errors<br/>' : '') + (data.warnings.num > 0 ? data.warnings.num + ' warnings' : '') + '</div>';
				html += '<form target="_blank" action="https://jigsaw.w3.org/css-validator/validator" method="post" enctype="multipart/form-data"><textarea style="display:none" name="text">' + String(data.src).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') + '</textarea><button type="submit">Show details</button></form>';
				$(element).find(".css-detail").html(html);
			}
		});
	}
	
	
	var auto = $('input:radio[name=w3cautovalidate]:checked').val();
	if(auto == 1) {
		$( element ).validateHtml();
		$( element ).validateCss();
		
	} else {
		$( element ).find(".w3c-query-html").on("click", function() {
			$( element ).validateHtml();
		}).css({cursor:'pointer'});

		$( element ).find(".w3c-query-css").on("click", function() {
			$( element ).validateCss();
		}).css({cursor:'pointer'});
	}
};


if (typeof jQuery != 'undefined') {
	initW3c.call(this, window.jQuery,".validator-elements");
	
} else {
	var c=document.createElement("script");
	c.setAttribute("type","text/javascript");
	c.setAttribute("charset","UTF-8");
	c.setAttribute("src","http://code.jquery.com/jquery-1.11.2.min.js");
	document.documentElement.appendChild(c);
	c.onload=c.onreadystatechange=function(){
		var a=c.readyState;
		if(!a||a==="loaded"||a==="complete"){
			c.onload=c.onreadystatechange=null;
			initW3c.call(this, window.jQuery, ".validator-elements");
		}
	}
}





