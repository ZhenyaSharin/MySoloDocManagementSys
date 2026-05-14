<template>
	<div class="modal fade" id="deleteAgreeModal" tabindex="-1" role="dialog" aria-labelledby="deleteAgreeModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="deleteAgreeModalTitle">
						Удалить обращение ???
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					{{ agreeText }}
				</div>
				<div class="modal-footer ta-center">
					<button class="btn btn-primary no-round flex-fill" @click="remove()">
	                    Удалить
	                </button>
	                <button class="btn btn-danger no-round flex-fill" data-dismiss="modal">
	                    Отмена
	                </button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			agreeId: Number,
			agreeText: String,
		},
		methods: {
			remove: function() {
				axios.post('api/updateagreement', {agreementId: this.agreeId, delete: 1}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						console.log(response.data.result.id);
						// this.userMessage = 1;
						this.$router.go();
					} else {
						// this.userMessage = 2;
						console.log(response.data);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					this.userMessage = 2;
					console.log(error);
				});
			}
		}
	}
</script>