<template>
	<div class="container">
		<div class="row">
			<vue-template-search :users-list="usersList"/>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			userId: String,
		},
		data() {
			return {
				usersList: [],
				searchMessage: false,
				countDocs: null,
				countAssign: null,
				doctypes: true,
				assigntypes: true,
			}
		}, 
		created() {
			this.$root.checkRole(this.userId);
			axios.post('api/getuserslist', {id: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						this.usersList = response.data.result;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
		},
		methods: {
			closeMsg: function() {
				this.searchMessage = 0;
			},
		},
	}
</script>