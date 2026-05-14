<template>
	<div class="modal fade" id="personalControlModal" tabindex="-1" role="dialog" aria-labelledby="personalControlModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title" id="personalControlModalTitle">
						<span class="font-up font-bold">отправить на личный контроль</span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="refreshCtrl()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body py-2 px-0  p-md-4">
					<div class="col-md-12">
						<div class="alert alert-warning d-flex justify-content-between align-items-center" v-if="err==1">
					    	<div>
					    		<i class="fas fa-exclamation-triangle fa-lg"></i>&nbsp;&nbsp;Вы не выбрали пользователя...
					    	</div>
					    </div>
					    <div v-else-if="err==2" class="alert alert-danger d-flex justify-content-between align-items-center">
					    	<div>
					    		<i class="fas fa-exclamation-circle fa-lg"></i>&nbsp;&nbsp; Возникла ошибка...
					    	</div>
					    </div>
                        <vue-multiselect v-model="$parent.valueCtrl" :options="list" placeholder="Выберите пользователя" label="title" track-by="id" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано" :searchable="true" :custom-label="$root.namesFull">
                        	<template slot="noOptions" slot-scope="props">
								Список пуст.
							</template>
							<template slot="noResult" slot-scope="props">
								Ничего не найдено...
							</template>
                        </vue-multiselect>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between align-items-center">
					<button v-if="$parent.isCreatingCtrl==true" type="button" class="btn btn-success no-round  font-bold" style="width: 48%">
						<vue-spinner/>
					</button>
					<button v-else type="button" class="btn btn-success no-round font-bold py-2 flex-fill" style="width: 48%" @click="send()">
						<i class="fas fa-user-check"/>&nbsp;&nbsp;Отправить
					</button>
					<button type="button" class="btn btn-danger no-round font-bold py-2 flex-fill"  style="width: 48%" data-dismiss="modal" @click="refreshCtrl()">
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
			list: Array,
		},
		data() {
			return {
				err: 0,
			}
		},
		methods: {
			send: function() {
				if (this.$parent.valueCtrl.length === 0) {
					this.err = 1;
				} else {
					this.err = 0;
					this.$parent.isCreatingCtrl = true;
					axios.post('api/addassigncontrol', {assignmentId: this.$parent.id, userId: this.$parent.valueCtrl.id, initiatorId: this.$parent.userId}, {
				        headers: {
				        	"Content-Type": "application/json"
				        }
				     })
						.then(response => {
							if (response.data.error == 0) {	
								this.err = 0;
								this.$parent.isCreatingCtrl = false;
								this.$root.refreshPage();
							} else {
								this.err = 2;
								this.$parent.isCreatingCtrl = false;
							}
						}).catch(error => {
							this.err = 2;
							alert('Ошибка получения данных');
							console.log(error);
							this.$parent.isCreatingCtrl = false;
						});
				}
			},
		}
	}
</script>