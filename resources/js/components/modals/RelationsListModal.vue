<template>
	<div class="modal fade" id="RelationsListModal" tabindex="-1" role="dialog" aria-labelledby="RelationsListLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="AgreementsListLabel">
						Список связей
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body table_scroll_998_y">
					<div class="alert alert-danger d-flex justify-content-between align-items-center" v-if="removeErr==true">
				    	<div>
				    		<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Не удалось удалить связь...
				    	</div>
				    	<div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="$root.openRelsFunc(false)">
				        	<i class="fas fa-times fa-lg"/>
				        </div>
				    </div>
				    <template v-if="authorId==userId">
				    	<template v-if="($root.filteredDocs.length > 0) && ($root.filteredAssigns.length > 0)">
				    		<vue-template-relationsadd/>
				    	</template>
				    	<template v-else>
				    		<div class="width100 ta-center greyblck my-4 pt-4">
								<vue-spinner/>
							</div>
				    	</template>
				    </template>

<!-- 					<pre>
						{{ $root.relationsList }}
					</pre> -->
					<table class="table table-striped mb-0">
						<thead class="thead-dark">
							<tr>
								<th class="px-2" scope="col">
									#
								</th>
								<th class="ta-center py-1" scope="col">
									Звено 1
								</th>
								<th class="ta-center py-1" scope="col">
									Тип
								</th>
								<th class="ta-center py-1" scope="col">
									Звено 2
								</th>
								<th class="ta-center py-1" scope="col">
									Тип
								</th>
								<th class="ta-right p-1" scope="col">
									Дата и время добавления связи
								</th>
								<th v-if="authorId==userId">
									
								</th>
							</tr>
						</thead>
						<tbody v-if="$root.relationsList.length!=0">
							<template v-for="(item, index) in $root.relationsList">
								<vue-item-relation v-if="item.mainId==null" :index="index" :data="item"/>	
							</template>
						</tbody>
						<tbody v-else>
							<tr class="tr-greyplug">
								<th colspan="100%" class="ta-center font-up">
									Список пуст
								</th>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			id: {
				type: String,
				default: null,
			},
			type: String,
			userId: String,
			authorId: {
				type: String,
				default: null,
			},
		},
		data() {
			return {
				relArr: [],
				docArr: [],
				assignArr: [],
				removeErr: false,
			}
		},
		created() {
			// this.getRelations();
		},
		methods: {
			removeRel: function(id) {
				axios.post('api/updaterelation', {
					id: id,
					remove: 1,
				}, {
			        headers: {
			        	"Content-Type": "application/json"
			        }
			    })
					.then(response => {
						if (response.data.error == 0) {
							console.log('Просмотрено');
							this.removeErr = false;
							this.$router.go();
						} else {
							this.removeErr = true;
						}
					}).catch(error => {
						alert('Ошибка получения данных7');
						this.removeErr = true;
						console.log(error);
					});
			},
			closeMsg: function() {
				this.removeErr = false;
			},
		}
	}
</script>