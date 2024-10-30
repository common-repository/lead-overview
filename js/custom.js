function getParameterByName(p){
    var match = RegExp('[?&]' + p + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

function strpos (haystack, needle, offset) {
  var i = (haystack + '')
    .indexOf(needle, (offset || 0))
  return i === -1 ? false : i
}

jQuery(document).ready(function(){
	
	var check_ref = strpos(document.referrer, php_vars.website_url , 0);
	//console.log(document.referrer);
	//console.log(php_vars.website_url);
	//console.log(check_ref);
	
	var source = getParameterByName('utm_source');
	var medium = getParameterByName('utm_medium');
	var campaign = getParameterByName('utm_campaign');
	var gclid = getParameterByName('gclid');
	var kw_field = getParameterByName('keyword');	
	
	if(Cookies.get('referer') != '' && Cookies.get('referer') != undefined){
		$('input[name="site-referer"]').val(Cookies.get('referer'));
	}
	if (check_ref !== false) {}
	else{
		$('input[name="site-referer"]').val(document.referrer);
		Cookies.set('referer', document.referrer);
	}
	
	if(source == null && Cookies.get('utm_source') != ''){
		$('input[name="utm_source"]').val(Cookies.get('utm_source'));
	}
	else{
		if(source == null){	source = '';}
		$('input[name="utm_source"]').val(source);
		Cookies.set('utm_source', source);
	}
	
	if(medium == null && Cookies.get('utm_medium') != ''){
		$('input[name="utm_medium"]').val(Cookies.get('utm_medium'));
	}
	else{
		if(medium == null){	medium = '';}
		$('input[name="utm_medium"]').val(medium);
		Cookies.set('utm_medium', medium);
	}
	
	if(campaign == null && Cookies.get('utm_campaign') != ''){
		$('input[name="utm_campaign"]').val(Cookies.get('utm_campaign'));
	}
	else{
		if(campaign == null){	campaign = '';}
		$('input[name="utm_campaign"]').val(campaign);
		Cookies.set('utm_campaign', campaign);
	}
	
	if(gclid == null && Cookies.get('gclid') != ''){
		$('input[name="GCLID__c"]').val(Cookies.get('gclid'));
	}
	else{
		if(gclid == null){	gclid = '';}
		$('input[name="GCLID__c"]').val(gclid);
		Cookies.set('gclid', gclid);
	}
	
	if(kw_field == null && Cookies.get('kw_field') != ''){
		$('input[name="kw_field"]').val(Cookies.get('kw_field'));
	}
	else{
		if(kw_field == null){	kw_field = '';}
		$('input[name="kw_field"]').val(kw_field);
		Cookies.set('kw_field', kw_field);
	}
});