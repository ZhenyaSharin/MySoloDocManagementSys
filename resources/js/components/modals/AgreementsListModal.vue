<template>
	<div class="modal fade" id="AgreementsListModal" tabindex="-1" role="dialog" aria-labelledby="AgreementsListLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="AgreementsListLabel">
						Список согласовантов
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body table_scroll_998_y">
					<table class="table table-striped">
						<thead class="thead-dark">
							<tr>
								<th scope="col">
									Согласовант
								</th>
								<th class="ta-center" scope="col">
									Комментарий
								</th>
								<th class="ta-center" scope="col">
									Дата создания
								</th>
								<th class="ta-center" scope="col">
									Дата решения
								</th>
								<th class="ta-right" scope="col">
									Статус
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
						<template v-else>
							<tbody v-if="docInfo!=null">
								<template v-for="item in docInfo.users">
									<tr v-if="item.approved_at!=null">
										<td class="font-bold">
											<vue-link-userfio :data="item.user" :short="true"/>
										</td>
										<td class="ta-center" v-if="item.note!=null">
											{{ item.note }}
										</td>
										<td class="ta-center" v-else>
											...
										</td>								
										<td class="ta-center">
											<vue-elem-timestamp :date-time="item.created_at"/>
										</td>
										<td class="ta-center" v-if="item.updated_at!=null">
											<vue-elem-timestamp :date-time="item.updated_at"/>
										</td>
										<td class="ta-center" v-else>
											...
										</td>
										<td class="font-bold ta-right status_approved">
											Согласовано
										</td>
									</tr>
									<tr v-else-if="item.refused_at!=null">
										<td class="font-bold">
											<vue-link-userfio :data="item.user" :short="true"/>
										</td>
										<td class="ta-center" v-if="item.note!=null">
											{{ item.note }}
										</td>
										<td class="ta-center" v-else>
											...
										</td>								
										<td class="ta-center">
											<vue-elem-timestamp :date-time="item.created_at"/>
										</td>
										<td class="ta-center" v-if="item.updated_at!=null">
											<vue-elem-timestamp :date-time="item.updated_at"/>
										</td>
										<td class="ta-center" v-else>
											...
										</td>
										<td class="font-bold ta-right status_refused">
											Отклонено
										</td>
									</tr>
									<tr v-else>
										<td class="font-bold">
											<vue-link-userfio :data="item.user" :short="true"/>
										</td>
										<td class="ta-center" v-if="item.note!=null">
											{{ item.note }}
										</td>
										<td class="ta-center" v-else>
											...
										</td>								
										<td v-if="item.created_at!=null" class="ta-center">
											<vue-elem-timestamp :date-time="item.created_at"/>
										</td>
										<td v-else class="ta-center">
											...
										</td>
										<td class="ta-center" v-if="item.updated_at!=null">
											<vue-elem-timestamp :date-time="item.updated_at"/>
										</td>
										<td class="ta-center" v-else>
											...
										</td>
										<td class="ta-right greytxt" v-if="docInfo.refused_at!=null&&item.updated_at==null&&item.created_at==null">
											<span class="font-bold">Отклонено</span>&nbsp;(автоматически)
										</td>
										<td v-else class="font-bold ta-right status_considering">
											На рассмотрении
										</td>
									</tr>
								</template>
							</tbody>
							<tbody v-else>
								<tr v-if="noAgr===true" class="tr-greyplug">
									<th colspan="100%" class="ta-center font-up">
										Без согласования
									</th>
								</tr>
								<tr v-else class="tr-greyplug">
									<th colspan="100%" class="ta-center font-up">
										Список пуст
									</th>
								</tr>
							</tbody>
						</template>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			docId: Number,
			userId: String,
		},
		data() {
			return {
				docInfo: null,
				spinOff: true,
				noAgr: false,
				// id: null
			}
		},
		// mounted() {
		// 	console.log(this.userId);
		// },
		created() {
			axios.post('api/getagreementsusersbydocid', {id: this.docId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						let users = response.data.result.users;
						if ((users.length === 1) && (users[0].userId == this.userId)) {
							this.docInfo = null;
							this.noAgr = true;
						} else {
							this.docInfo = response.data.result;
						}
						this.spinOff = false;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
		},
	}
</script>