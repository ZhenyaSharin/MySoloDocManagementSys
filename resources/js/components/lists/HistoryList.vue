<template>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header font-bold hptable d-flex justify-content-between align-items-center">
						<div class="font-bold font-up">
							История Ваших согласований
						</div>
					</div>
					<div class="card-body table_scroll_1200_y">
						<table class="table table-hover">
							<thead class="thead-dark">
								<tr>
									<th scope="col" width="160px">
										Документ
									</th>
									<th class="ta-center" scope="col" width="140px">
										Автор
									</th>
									<th class="ta-center" scope="col">
										Тип
									</th>
									<th class="ta-center" scope="col">
										Дата создания
									</th>
									<th class="ta-center" scope="col">
										Дата изменения
									</th>
									<th scope="col" class="ta-center" width="130px">
										Ваше решение
									</th>
									<th scope="col" width="160px" class="ta-right">
										Статус док-та
									</th>
								</tr>
							</thead>
							<template v-if="spinOffHistory===true">
								<tbody>
									<tr>
										<td colspan="100%" class="ta-center mt-4">
											<vue-spinner/>
										</td>
									</tr>
								</tbody>
							</template>
							<template v-else-if="spinOffHistory===false">
								<tbody v-if="historyLog.length>0">
									<template v-for="item in historyLog">
										<vue-item-historylog :data="item"/>
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
				spinOffHistory: true,
			}
		},
		mounted() {
			axios.post('api/agreementsusershistory', {userId: this.userId}, {
				headers: {
					"Content-Type": "application/json"
				}
			})
				.then(response => {
					if (response.data.error == 0) {
						this.historyLog = response.data.result;
						// console.log(this.historyLog);
						this.spinOffHistory = false;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
		},
		methods: {
			// 
		},
	}
</script>