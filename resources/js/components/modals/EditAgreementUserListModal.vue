<template>
	<div class="modal fade" id="editAgreementUserListModal" tabindex="-1" role="dialog" aria-labelledby="editAgreementUserListTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="editAgreementUserListTitle">
						Редактировать список согласовантов
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div>
						<h4>
							Добавить в список согласовантов:
						</h4>
						<vue-multiselect class="my-4" v-model="value" :options="users" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" :max="10" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано" no-options="Список пуст">
							<template slot="selection" slot-scope="{ values, search, isOpen }">
								<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Пользователей выбрано: {{ values.length }}</span>
							</template>
							<template slot="noOptions" slot-scope="props">
								Список пуст.
							</template>
							<template slot="noResult" slot-scope="props">
								Ничего не найдено...
							</template>
						</vue-multiselect>
						<template v-if="orderable==true">
							<div v-for="(item, index) in value" class="font-bold m-2">
								{{ order+index+1 }}.&nbsp;&nbsp;{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
							</div>
						</template>
						<template v-else>
							<div v-for="item in value" class="font-bold m-2">
								<i class="fas fa-check-circle"/>&nbsp;&nbsp;{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
							</div>
						</template>
					</div>
					<hr/>
					<div class="table_scroll_998_y">
						<table class="table table-striped">
							<thead class="thead-dark">
								<tr>
									<th v-if="orderable==true" scope="col" width="10px">
										#
									</th>
									<th scope="col" width="170px">
										ФИО
									</th>
									<th scope="col" class="ta-center px-1">
										Дата решения
									</th>
									<th scope="col" class="ta-center">
										Комментарий
									</th>
									<th scope="col" class="ta-center">
										Статус
									</th>
									<th v-if="orderable==false" scope="col" class="ta-right px-1 pr-2">
										Действие
									</th>
								</tr>
							</thead>
							<template v-if="spinOff===true">
								<tbody>
									<tr>
										<td colspan="100%" class="ta-center mt-4">
											<vue-spinner/>
										</td>
									</tr>
								</tbody>
							</template>
							<template v-else-if="spinOff===false">
								<tbody v-if="list.length>0">
									<tr v-for="item in list">
										<td class="ta-center" v-if="orderable==true">
											{{ item.order }}
										</td>
										<td>
											<vue-link-userfio :data="item.user" :short="true"/>
										</td>
										<td class="ta-center"> 
											<vue-elem-timestamp :date-time="item.created_at"/>&nbsp;<span v-if="item.updated_at" class="redacted">(Изм.)</span>
										</td>
										<td class="ta-center px-1" v-if="item.note!=null">
											{{ item.note }}
										</td>
										<td class="ta-center" v-else>
											...
										</td>
										<td v-if="item.approved_at!=null" class="status_approved font-bold ta-right">
											<template v-if="item.length==1">
												Подписано
											</template>
											<template v-else>
												Согласовано
											</template>
										</td>
										<td v-else-if="item.refused_at!=null" class="status_refused font-bold ta-right">
											Отклонено
										</td>
										<td v-else class="status_considering font-bold ta-right">
											На согласовании
										</td>
										<template v-if="orderable==false">
											<td v-if="item.approved_at==null&&item.refused_at==null" scope="col" class="ta-right">
												<button v-if="list.length!=1" class="btn btn-danger no-round btn-squarebtn" title="Удалить заявку" @click="remove(item.id)">
													<i class="fas fa-times fa-lg"/>
												</button>
												<button v-else class="btn btn-secondary no-round btn-squarebtn" disabled title="Сначала добавьте новых согласовантов">
													<i class="fas fa-times fa-lg"/>
												</button>
											</td>
											<td v-else scope="col" class="ta-right">
												<button class="btn btn-secondary no-round btn-squarebtn" disabled title="Рассмотренное согласование">
													<i class="fas fa-times fa-lg"/>
												</button>
											</td>
										</template>
									</tr>
								</tbody>
								<tbody v-else>
									<tr>
										<th colspan="100%" class="ta-center font-up">
											Список пуст
										</th>
									</tr>
								</tbody>
							</template>
						</table>
					</div>
				</div>
				<div class="modal-footer d-flex">
	                <button v-if="value!=[]" class="btn btn-primary no-round flex-fill" @click="add()">
	                    Добавить
	                </button>
	                <button v-else class="btn btn-primary no-round flex-sm-fill" disabled>
	                    Добавить
	                </button>
	                <button type="button" class="btn btn-danger no-round flex-fill" data-dismiss="modal">
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
			documentId: Number,
			status: Number,
			orderable: Boolean,
			list: Array,
			order: Number,
			users: Array,
			agreementId: Number,
			completed: Boolean,
		},
		data() {
			return {
				spinOff: true,
				value: [],
			}
		},
		mounted() {
			this.spinOff = false;
			console.log
		},
		methods: {
			remove: function(id) {
				this.spinOff = false;
				let isComplete = true;
				let data = {};
				this.list.forEach(item => {
					if (item.id !== id) {
						if (item.approved_at === null) {
							isComplete = false;
						}
					};
				});
				console.log(isComplete);
				data = (isComplete === true) ? {id: id, delete: 1, docComplete: this.documentId } : {id: id, delete: 1};
				axios.post('api/removeagreeuser', data, {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.$router.go();
							this.spinOff = false;
						} else {
							this.spinOff = false;
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных9');
						console.log(error);
					});
			},
			add: function() {
				let data = {
					id: this.agreementId,
					documentId: this.documentId,
					status: this.status,
					users: [],
				};
				let order = this.order + 1;
				if (this.orderable === true) {
					this.value.forEach(item => {
						data.users.push({
							userId: item.id,
							order: order++,
						});
					});
					data.orderable = 1;
					data.completed = (this.completed === true) ? 1 : null;
				} else {
					this.value.forEach(item => {
						data.users.push({
							userId: item.id,
							order: null,
						});
					});
					data.orderable = null;
				}
				// console.log(data);
				axios.post('api/updateagreelist', data, {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.$router.go();
							this.spinOff = false;
						} else {
							this.spinOff = false;
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных9');
						console.log(error);
					});
			},
		},
	}
</script>