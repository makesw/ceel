jQuery(document).on('submit','#formTest1', function(event){
    $.ajax({
        url: 'testAjax.php',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});

jQuery(document).on('submit','#formTest2', function(event){
	event.preventDefault();	
	jQuery.ajax({
		url: 'testAjax.php',
		type:'POST',
		dataType: 'json',
		data: new FormData(this)
	})
	.done(function(respuesta){	  
		if( !respuesta.error ){
			console.log('done...');			
		}else{
			console.log('error...');
			console.log(resp.responseText);
		}
	})
	.fail(function(resp){
		console.log('fail..');		
				console.log(resp.responseText);	
	})
	});