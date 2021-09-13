$('.search-box').hide();
$('#input-search').on('keyup', function() {
	var rex = new RegExp($(this).val(), 'i');
	if(rex == '/(?:)/i'){
		$('.search-box').hide();
	} else{
		$('.search-box').show();
		$('.search-box .items').hide();
		$('.search-box .items').filter(function() {
			console.log(rex.test($(this).text()));
			return rex.test($(this).text());
		}).show();
	}
});