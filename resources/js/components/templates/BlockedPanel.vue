<template>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			    <div class="alert alert-danger">
			    	Ваш профиль заблокирован...
			    </div>
			</div>
			<br/>
			<div class="col-md-12">
				Вы можете связаться с администратором
				<br/>
				<br/>
				<i class="fas fa-envelope fa-lg"/>&nbsp;&nbsp;<a href="mailto:admin@yourmail.ru" class="font-bold">admin@yourmail.ru</a>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			userId: String,
		}
	}
</script>