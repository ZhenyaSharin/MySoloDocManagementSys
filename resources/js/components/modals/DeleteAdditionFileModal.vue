<template>
	<div class="modal fade" id="deleteAdditionFileModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdditionFileModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="deleteAdditionFileModalTitle">
						Удалить файл приложения?
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- <a v-if="$root.storage=='coresar'" :href="'/storage/app/public/additions/'+ data.file" download>
						<i class="fas fa-file-download fa-lg"/>&nbsp;&nbsp;{{ data.file }}
					</a>
					<a v-else :href="'/storage/additions/'+ data.file" download>
						<i class="fas fa-file-download fa-lg"/>&nbsp;&nbsp;{{ data.file }}
					</a> -->
					<a :href="'/storage/additions/'+ data.file" download>
						<i class="fas fa-file-download fa-lg"/>&nbsp;&nbsp;{{ data.file }}
					</a>
				</div>
				<div class="modal-footer ta-center">
					<button class="btn btn-primary no-round flex-fill" @click="remove()">
	                    Удалить
	                </button>
	                <button class="btn btn-danger no-round flex-fill" data-dismiss="modal" id="fileItemCloseModal">
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
			data: Object,
		},
		methods: {
			remove: function() {
				axios.post('api/updatefileaddition', {id: this.data.id, delete: 1}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						console.log(response.data.result.id);
						this.$router.go();
						// document.getElementById('fileItemCloseModal').click();
						// this.$parent.makeFileArr();
					} else {
						console.log(response.data);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					this.userMessage = 2;
					console.log(error);
				});
			},
		}
	}
</script>