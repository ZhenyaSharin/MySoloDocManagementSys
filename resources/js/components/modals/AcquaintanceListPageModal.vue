<template>
	<div class="modal fade" id="acquaintanceListPageModal" tabindex="-1" role="dialog" aria-labelledby="acquaintanceListPageTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title" id="acquaintanceListPageTitle">
						<span class="font-up font-bold">Список на ознакомление</span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="col-md-12 table_scroll_998_y">
						<table class="table table-striped">
							<thead class="thead-dark">
								<tr>
									<th scope="col">
										Пользователь
									</th>
									<th class="ta-center" scope="col">
										Дата отправки
									</th>
									<th class="ta-right" scope="col">
										Дата ознакомления
									</th>
									<th width="60px" class="ta-center" scope="col">
										Действие
									</th>
								</tr>
							</thead>
							<tbody v-if="$parent.spinOffAcqModal===true">
								<tr>
									<td colspan="100%" class="ta-center mt-4">
										<vue-spinner/>
									</td>
								</tr>
							</tbody>
							<tbody v-else>
								<template v-if="list.length==0">
									<tr class="tr-greyplug">
										<th colspan="100%" class="ta-center font-up">
											Список пуст
										</th>
									</tr>
								</template>
								<template v-else>
									<tr v-for="item in list">
										<td>
											{{ $root.makeFio(item.user.surname, item.user.firstname, item.user.patronymic) }}
										</td>
										<td class="ta-center">
											<vue-elem-timestamp :date-time="item.created_at"/>
										</td>
										<td v-if="item.seen_at==null" class="ta-right">
											...
										</td>
										<td class="ta-right" v-else>
											<vue-elem-timestamp :date-time="item.seen_at"/>
										</td>
										<td scope="col" class="ta-right" v-if="item.initiatorId==$parent.userId">
											<button v-if="item.removed==null&&item.seen_at==null" class="btn btn-danger no-round btn-squarebtn" title="Удалить заявку" @click="removeAcq(item.id)">
												<i class="fas fa-times fa-lg"/>
											</button>
											<button v-else class="btn btn-secondary no-round btn-squarebtn" title="Удалить заявку" disabled>
												<i class="fas fa-times fa-lg"/>
											</button>
										</td>
										<td class="ta-right" v-else>
											...
										</td>
									</tr>
								</template>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			// docId: Number,
			list: Array,
		},
		// data() {
		// 	return {
		// 		spinOffAcqModal: true,
		// 	}
		// },
		methods: {
			removeAcq: function(id) {
				axios.post('api/updateacquaintance', {id: id, delete: 1}, {
			        headers: {
			        	"Content-Type": "application/json"
			        }
			    })
					.then(response => {
						if (response.data.error == 0) {
							this.$parent.getAcqDocList();
							console.log('удалён');
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						this.userMessage = 2;
						console.log(error);
					});
			}
		},
	}
</script>