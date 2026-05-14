<template>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<br/>
				<div class="card">
					<div class="card-header font-bold d-flex justify-content-between">
						<div>
							<span class="font-bold font-up">
								Актуальные заявки на согласование
							</span>
						&nbsp;</span><span class="greytxt" v-if="countNew>0">(&nbsp;новых:<span class="status_refused font-bold">&nbsp;{{ countNew }}&nbsp;</span>)</span><span class="greytxt" v-else>(&nbsp;новых заявок нет)</span>&nbsp;
						</div>
					</div>
					<div class="card-body table_scroll_998_y">
						<table class="table table-hover">
							<thead class="thead-dark">
								<tr>
									<th scope="col">
										Документ
									</th>
									<th class="ta-center" scope="col">
										Тип документа
									</th>
									<th class="ta-center" scope="col">
										Статус
									</th>
									<th class="ta-center" scope="col">
										Дата создания
									</th>
									<th class="ta-center" scope="col">
										Согласовать до
									</th>
									<th class="ta-right" scope="col">
										Автор
									</th>
								</tr>
							</thead>
							<template v-if="spinOffAgrs===true">
								<tbody>
									<tr>
										<td colspan="100%" class="ta-center mt-4">
											<vue-spinner/>
										</td>
									</tr>
								</tbody>
							</template>
							<template v-else-if="spinOffAgrs===false">
								<tbody v-if="agreeList.length>0">
									<template v-for="item in agreeList">
										<vue-item-agreementitem :data="item" :user-id="userId" document-id="item.agreement.documentId"/>
									</template>
								</tbody>
								<tbody v-else>
									<tr class="tr-greyplug">
										<th colspan="100%" class="ta-center font-up">
											Ничего не найдено
										</th>
									</tr>
								</tbody>
							</template>
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
			userId: String,
		},
		data() {
			return {
				spinOffAgrs: true,
				agreementDoc: {
					userId: this.userId,
					docId: null,
					docAgId: null,
					docAgName: null,
					agrId: null,
					docFile: null,
				},
				countNew: 0,
			}
		},
		mounted() {
			this.getAgreements();
			// this.getNonViewed();
		},
		methods: {
			getAgreements: function() {
				this.spinOffAgrs = true;
				this.countNew = 0;
				axios.post('api/getagreementslistbyuser', {userId: this.userId}, {
				headers: {
					"Content-Type": "application/json"
				}
			})
				.then(response => {
					if (response.data.error == 0) {
						this.agreeList = response.data.result;
						this.agreeList.forEach(item => {
							if (item.viewed_at == null) {
								this.countNew++;
							}
						});
						console.log(this.agreeList);
						this.spinOffAgrs = false;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
			},
            refreshPageAgreement: function() {
            	// this.getNonViewed();
                this.getAgreements();
            },
		}
	}
</script>