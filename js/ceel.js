function profileEdit() {
	$('#prof-mail').prop('disabled', false);
	$('#prof-codigo').prop('disabled', false);
	$('#prof-cargo').prop('disabled', false);
	$('#prof-sede').prop('disabled', false);
	$('#avatarFiele').prop('disabled', false);	
	$('#div-editprof').hide();
	$('#div-saveprof').show();
}
function profileEditUser() {
	$('#prof-mail').prop('disabled', false);
	$('#avatarFiele').prop('disabled', false);	
	$('#div-editprof').hide();
	$('#div-saveprof').show();
}
function profileEditAdmin() {
	$('#prof-mail').prop('disabled', false);
	$('#avatarFiele').prop('disabled', false);	
	$('#div-editprof').hide();
	$('#div-saveprof').show();
}
function fn_edit_user( id ) {	 
	location.href= './users-edit.php?idUser='+id;		
}
function fn_view_user( id ) {	 
	location.href= './users-view.php?idUser='+id;		
}
function fn_edit_curse( id ) {	 
	location.href= './courses-edit.php?idCourse='+id;		
}
function fn_edit_unity( idcurso, idunidad ) {	 
	location.href= './courses-edit-unity.php?course_id='+idcurso+'&unity_id='+idunidad;		
}
function fn_edit_lesson( idcurso, idunidad, idLeccion ) {	 
	location.href= './courses-edit-lesson.php?course_id='+idcurso+'&unity_id='+idunidad+'&lesson_id='+idLeccion;		
}
function fn_edit_question( idPregunta, idLeccion,  idCurso, idUnidad) {	 
	location.href= './courses-edit-question.php?question_id='+idPregunta+'&lesson_id='+idLeccion+'&course_id='+idCurso+'&unity_id='+idUnidad;	
}
jQuery(document).on('submit','#formProfile', function(event){
	 $.ajax({
        url: 'actions.php?action=editProfile',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
			console.log(data);
			location.reload();
        },
		error: function (data) {
			console.log(data);    
        },
        cache: false,
        contentType: false,
        processData: false,
    });

    return false;
	
	
});
jQuery(document).on('submit','#form-add-user', function(event){
	event.preventDefault();	
	jQuery.ajax({
		url: 'actions.php?action=createUser',
		type:'POST',
		dataType: 'json',
		data: $(this).serialize()
	})
	.done(function(respuesta){	  
		if( !respuesta.error ){
			//console.log('done...'+respuesta);
			$('#div-msg-ok').show();
			setTimeout(function(){
				$('#div-msg-ok').hide();
				if(respuesta.optButton == 'save_back'){
					window.history.back();
				}else{
					location.reload();
				}			
			},2000);
		}else{
			//console.log('error...'+respuesta);
			$('#div-msg-fail-desc').text(respuesta.description);
			$('#div-msg-fail').show();
			setTimeout(function(){
				$('#div-msg-fail').hide();
			},2000);
		}
	})
	.fail(function(respuesta){	
		//console.log('fail..'+respuesta.responseText);
		$('#div-msg-fail-desc').text('Usuario creado pero no se pudo enviar correo');
		$('#div-msg-fail').show();
		setTimeout(function(){
			$('#div-msg-fail').hide();	
			location.reload();
		},2000);
	})
	.always(function(respuesta){ 
	})
});
jQuery(document).on('submit','#form-edit-user', function(event){
	event.preventDefault();	
	jQuery.ajax({
		url: 'actions.php?action=editUser',
		type:'POST',
		dataType: 'json',
		data: $(this).serialize()
	})
	.done(function(respuesta){	  
		if( !respuesta.error ){
			console.log('done...');
			$('#div-msg-ok-desc').text(respuesta.description);
			$('#div-msg-ok').show();
			setTimeout(function(){
				$('#div-msg-ok').hide();
			if(respuesta.optButton == 'save_back'){
					window.history.back();
			}else{
				location.reload();
			}	
			},2000);			
		}else{
			console.log('error...');
			$('#div-msg-fail-desc').text(respuesta.description);
			$('#div-msg-fail').show();
		}
		setTimeout(function(){
				$('#div-msg-ok').hide();	
			},2000);
		console.log(respuesta);
	})
	.fail(function(resp){
		console.log('fail..');		
				console.log(resp.responseText);	
	})
	.always(function(respuesta){ 
	})
});
jQuery(document).on('submit','#form-create-course', function(event){
	var allowedExtensionsImg = /(\.jpg|\.jpeg|\.png)$/i;
	var allowedExtensionsCompress = /(\.zip)$/i;
	var iconInput = document.getElementById('iconoCurso');
	var contentInput = document.getElementById('archivo');	
	var imgInput = document.getElementById('imgCurso');			
    if( iconInput.value!='' && !allowedExtensionsImg.exec(iconInput.value) ){
		alert('Imagen de icono inválida, premitido:(.jpeg/.jpg/.png).');
	}else if( imgInput.value!='' && !allowedExtensionsImg.exec(imgInput.value) ){
		alert('Imagen de curso inválida, premitido:(.jpeg/.jpg/.png).');
	}else if( contentInput.value!='' && !allowedExtensionsCompress.exec(contentInput.value) ){
		alert('Contenido inválido, premitido:(.zip).');
	}else{
		$('#loadingDiv').show();
		$(':input[type="submit"]').prop('disabled', true);
		$.ajax({
			url: 'actions.php?action=createCourse',
			type: 'POST',
			data: new FormData(this),
			success: function (data) {
				$('#loadingDiv').hide();
				 $(':input[type="submit"]').prop('disabled', false);
				//console.log(data);
				$('#div-msg-ok').show();
				setTimeout(function(){
					$('#div-msg-ok').hide();
					if(data.optButton == 'save_back'){
						window.history.back();
					}else{
						location.reload();
					}			
				},2000);
			},
			error: function (data) {
				$('#loadingDiv').hide();
				 $(':input[type="submit"]').prop('disabled', false);
				//console.log(data);
				$('#div-msg-fail').show();
				setTimeout(function(){
					$('#div-msg-fail').hide();
				},2000);
			},
			cache: false,
			contentType: false,
			processData: false
		});
	}
    return false;
});
jQuery(document).on('submit','#form-edit-course', function(event){
	var allowedExtensionsImg = /(\.jpg|\.jpeg|\.png)$/i;
	var allowedExtensionsCompress = /(\.zip)$/i;
	var iconInput = document.getElementById('iconoCurso');
	var contentInput = document.getElementById('archivo');	
	var imgInput = document.getElementById('imgCurso');			
    if( iconInput.value!='' && !allowedExtensionsImg.exec(iconInput.value) ){
		alert('Imagen de icono inválida, premitido:(.jpeg/.jpg/.png).');
	}else if( imgInput.value!='' && !allowedExtensionsImg.exec(imgInput.value) ){
		alert('Imagen de curso inválida, premitido:(.jpeg/.jpg/.png).');
	}else if( contentInput.value!='' && !allowedExtensionsCompress.exec(contentInput.value) ){
		alert('Contenido inválido, premitido:(.zip).');
	}else{
		$.ajax({
			url: 'actions.php?action=editCourse',
			type: 'POST',
			data: new FormData(this),
			success: function (data) {
				console.log(data);
				$('#div-msg-ok').show();
				setTimeout(function(){
					$('#div-msg-ok').hide();
					location.reload();
				},2000);
			},
			error: function (data) {
				console.log(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
	}

    return false;
});
jQuery(document).on('submit','#form-create-unity', function(event){
    $.ajax({
        url: 'actions.php?action=createUnity',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			$('#div-msg-ok').show();
			setTimeout(function(){
				$('#div-msg-ok').hide();
				if(data.optButton == 'save_back'){
					window.history.back();
				}else{
					location.reload();
				}			
			},2000);
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});
jQuery(document).on('submit','#form-edit-unity', function(event){
    $.ajax({
        url: 'actions.php?action=editUnity',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			$('#div-msg-ok').show();
			setTimeout(function(){
				$('#div-msg-ok').hide();
				location.href= './courses-edit-unity.php?course_id='+data.course_id+'&unity_id='+data.unity_id;
			},2000);
        },
		error: function (data) {
            console.log(data);
			$('#div-msg-fail').show();
			setTimeout(function(){
				$('#div-msg-fail').hide();
			},2000);
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});
jQuery(document).on('submit','#form-create-lesson', function(event){
    $.ajax({
        url: 'actions.php?action=createLesson',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			$('#div-msg-ok').show();
			setTimeout(function(){
				$('#div-msg-ok').hide();
				if(data.optButton == 'save_back'){
					window.history.back();
				}else{
					location.reload();
				}			
			},2000);
        },
		error: function (data) {
            console.log(data);
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
});
jQuery(document).on('submit','#form-edit-lesson', function(event){
    $.ajax({
        url: 'actions.php?action=editLesson',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			$('#div-msg-ok').show();
			setTimeout(function(){
				$('#div-msg-ok').hide();
				location.reload();
			},2000);
        },
		error: function (data) {
            console.log(data);
			$('#div-msg-fail').show();
			setTimeout(function(){
				$('#div-msg-fail').hide();
			},2000);
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;
});
jQuery(document).on('submit','#form-edit-question', function(event){
    $.ajax({
        url: 'actions.php?action=editQuestion',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			if(!data.error){
				$('#div-msg-ok').show();
				setTimeout(function(){
					$('#div-msg-ok').hide();
					window.history.back();
				},2000);
			}else{
				$('#div-msg-fail').show();
				setTimeout(function(){
					$('#div-msg-fail').hide();
					window.history.back();
				},2000);
			}			
        },
		error: function (data) {
            console.log(data);
			$('#div-msg-fail').show();
			setTimeout(function(){
				$('#div-msg-fail').hide();
				window.history.back();
			},2000);
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
});
jQuery(document).on('submit','#formContact', function(event){	
	 $.ajax({
        url: '/sendMail.php?action=contactar',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			if( !data.error ){
				$('#div-msg-ok-cont').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}else{
				$('#div-msg-fail-cont').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}			
        },
		error: function (data) {
			console.log(data);
			$('#div-msg-fail-cont').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
});
jQuery(document).on('submit','#form-remove-question', function(event){
    $.ajax({
        url: 'actions.php?action=deleteQuestions',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			if( !data.error ){
				$('#div-msg-ok-desc').text(data.description);
				$('#div-msg-ok').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}else{
				$('#div-msg-fail-desc').text(data.description);
				$('#div-msg-fail').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}			
        },
		error: function (data) {
			console.log(data);    
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
});
jQuery(document).on('submit','#form-remove-lesson', function(event){
    $.ajax({
        url: 'actions.php?action=deleteLessons',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			if( !data.error ){
				$('#div-msg-ok-desc').text(data.description);
				$('#div-msg-ok').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}else{
				$('#div-msg-fail-desc').text(data.description);
				$('#div-msg-fail').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}			
        },
		error: function (data) {
			console.log(data);    
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
});
jQuery(document).on('submit','#form-remove-unity', function(event){
    $.ajax({
        url: 'actions.php?action=deleteUnitys',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			if( !data.error ){
				$('#div-msg-ok-desc').text(data.description);
				$('#div-msg-ok').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}else{
				$('#div-msg-fail-desc').text(data.description);
				$('#div-msg-fail').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}			
        },
		error: function (data) {
			console.log(data);    
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
});
jQuery(document).on('submit','#formRecord', function(event){
   $.ajax({
        url: 'sendMail.php?action=recordar',
        type: 'POST',
        data: new FormData(this),
        success: function (data) {
            console.log(data);
			if( !data.error ){
				$('#div-msg-ok').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}else{
				$('#div-msg-fail').show();
				setTimeout(function(){
					window.location.reload();
				},2000);
			}			
        },
		error: function (data) {
			console.log(data);    
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
});

