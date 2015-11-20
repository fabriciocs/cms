{% extends "template.html" %}
{%block head%}
<?php
?>
{%block more_css%}
{{parent()}}
<link rel="stylesheet" href="{{base_url}}/{{templateName}}/assets/plugins/flexslider/flexslider.css">     
<link rel="stylesheet" href="{{base_url}}/{{templateName}}/assets/plugins/sky-forms/version-2.0.1/css/custom-sky-forms.css">

<!-- CSS Page Style -->    
<link rel="stylesheet" href="{{base_url}}/{{templateName}}/assets/css/pages/page_contact.css">

<!--[if lt IE 9]>
	<link rel="stylesheet" href="assets/plugins/sky-forms/version-2.0.1/css/sky-forms-ie8.css">
<![endif]-->
{%endblock%}
{{parent()}}
{%endblock%}
{%set menu_url='contato'%}
{% block content %}
<!--=== Content Part ===-->
<div class="container content">
	<div class="row margin-bottom-30">
		<div class="col-md-9 mb-margin-bottom-30">
			<div class="headline"><h2>Formulário de Contato</h2></div>
			<form action="{{base_url}}/email" method="post" id="sky-form3" class="sky-form sky-changes-3">
				<fieldset>                  
					<div class="row">
						<section class="col col-6">
							<label class="label">Nome</label>
							<label class="input">
								<i class="icon-append fa fa-user"></i>
								<input type="text" name="nome" id="nome">
							</label>
						</section>
						<section class="col col-6">
							<label class="label">E-mail</label>
							<label class="input">
								<i class="icon-append fa fa-envelope-o"></i>
								<input type="email" name="email" id="email">
							</label>
						</section>
					</div>

					<section>
						<label class="label">Assunto</label>
						<label class="input">
							<i class="icon-append fa fa-tag"></i>
							<input type="text" name="assunto" id="assunto">
						</label>
					</section>

					<section>
						<label class="label">Mensagem</label>
						<label class="textarea">
							<i class="icon-append fa fa-comment"></i>
							<textarea rows="4" name="mensagem" id="mensagem"></textarea>
						</label>
					</section>

					<section>
						<label class="label">Digite os caracteres:</label>
						<label class="input input-captcha">
							<img src="{{base_url}}/{{templateName}}/assets/plugins/sky-forms/version-2.0.1/captcha/image.php?{{time}}" width="100" height="32" alt="Captcha image" />
							<input type="text" maxlength="6" name="captcha" id="captcha">
						</label>
					</section>

					<section>
						<label class="checkbox"><input type="checkbox" name="copy"><i></i>Envie-me uma cópia</label>
					</section>
				</fieldset>

				<footer>
					<button type="submit" class="btn-u">Enviar</button>
				</footer>

				<div class="message">
					<i class="rounded-x fa fa-check"></i>
					<p>Sua mensagem foi enviada com sucesso!</p>
				</div>
			</form>
		</div><!--/col-md-9-->

		<div class="col-md-3">
			<!-- Contacts -->
			<div class="headline"><h2>Contatos</h2></div>
			<ul class="list-unstyled who margin-bottom-30">
				<li><a href="#"><i class="fa fa-home"></i>{{empresa.endereco}}</a></li>
				<li><a href="mailto:{{empresa.emailContato}}"><i class="fa fa-envelope"></i>{{empresa.emailContato}}</a></li>
				<li><a href="#"><i class="fa fa-phone"></i>{{empresa.telefone}}</a></li>
				<li><a href="#"><i class="fa fa-globe"></i>{{empresa.dominio}}</a></li>
			</ul>
		</div><!--/col-md-3-->
	</div><!--/row-->
	<!-- Google Map -->
	{%if empresa.iframeGoogleMaps%}
	<div class="embed-responsive embed-responsive-16by9 height-250">
		{{empresa.iframeGoogleMaps | raw}}
	</div>
	{%endif%}
</div>
<!-- Google Map -->
<!--				<div>
					<iframe
						width="600"
						height="450"
						frameborder="0" style="border:0"
						src="{{empresa.urlGoogleMaps}}">
					</iframe>
				</div>-/map-->
</div>  
{% endblock %}
{%block scripts%}
{{parent()}}
{%block more_scripts%}
{{parent()}}
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="{{base_url}}/{{templateName}}/assets/plugins/gmap/gmap.js"></script>
<script type="text/javascript" src="{{base_url}}/{{templateName}}/assets/plugins/flexslider/jquery.flexslider-min.js"></script>
<!-- Login Form -->
<script src="{{base_url}}/{{templateName}}/assets/plugins/sky-forms/version-2.0.1/js/jquery.form.min.js"></script>
<!-- Validation Form -->
<script src="{{base_url}}/{{templateName}}/assets/plugins/sky-forms/version-2.0.1/js/jquery.validate.min.js"></script>
<!-- JS Customization -->
<script type="text/javascript" src="{{base_url}}/{{templateName}}/assets/js/custom.js"></script>
<!-- JS Page Level -->           
<script type="text/javascript" src="{{base_url}}/{{templateName}}/assets/js/app.js"></script>
<script type="text/javascript" src="{{base_url}}/{{templateName}}/assets/js/pages/page_contact_advanced.js"></script>
<script type="text/javascript">
jQuery(document).ready(function () {
	App.init();
	App.initSliders();
	PageContactForm.initPageContactForm();
});
</script>
<!--[if lt IE 9]>
	<script src="assets/plugins/respond.js"></script>
	<script src="assets/plugins/html5shiv.js"></script>
	<script src="assets/js/plugins/placeholder-IE-fixes.js"></script>
	<script src="assets/plugins/sky-forms/version-2.0.1/js/sky-forms-ie8.js"></script>
<![endif]-->
<!--[if lt IE 10]>
	<script src="assets/plugins/sky-forms/version-2.0.1/js/jquery.placeholder.min.js"></script>
<![endif]-->
</script>
{%endblock%}
{%endblock%}