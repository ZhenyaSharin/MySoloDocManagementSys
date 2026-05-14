<template>
	<div class="modal fade" id="deleteAssignAuthorModal" tabindex="-1" role="dialog" aria-labelledby="deleteAssignAuthorModalTitle" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="deleteAssignAuthorModalTitle">
						Удалить поручение ?
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-footer ta-center">
					<button class="btn btn-primary no-round flex-fill" @click="remove()" data-dismiss="modal">
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
			id: Number,
		},
		methods: {
			remove: function() {
				axios.post('api/updateassignstatus', {assignmentId: this.id, alias: 'rejected_by_author'}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						if (this.$parent.authorAssignCount) {
							if (this.$parent.authorAssignCount != null) {
								this.$parent.getAssignmentsByAuthor(this.$parent.authorAssignCount);
							} else {
								this.$parent.assignsById();
							}
						} else {
							this.$router.go();
						}
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