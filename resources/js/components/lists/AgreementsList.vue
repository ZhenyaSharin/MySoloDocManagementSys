<template>
	<div class="container">
		<br/>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header font-bold font-up">
						Рассмотрение Ваших заявок на согласование
					</div>
					<div class="card-body table_scroll_998_y">
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th scope="col">
										Документ
									</th>
									<th class="ta-center" scope="col">
										Согласовант
									</th>
									<th class="ta-center" scope="col">
										Тип
									</th>
									<th class="ta-center" scope="col">
										Дата создания
									</th>
									<th class="ta-center" scope="col">
										Статус
									</th>
									<th class="ta-right" scope="col">
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
								<tbody v-if="responsesList.length>0">
									<template v-for="item in responsesList">
										<vue-item-responseitem :data="item" :getDocId="getDocId" :getAgreeId="getAgreeId" :getSendAgainData="getSendAgainData"/>
									</template>
									<!-- 									<pre>
										{{ docsList }}
									</pre> -->
								</tbody>
								<tbody v-else>
									<tr>
										<th colspan="100%" class="ta-center font-up tr-greyplug">
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
		<template v-if="documentId!=null">
			<vue-modal-agreementslist :doc-id="documentId" :key="documentId"/>
		</template>
		<!-- <vue-modal-sendagain :data="usersList" :user-id="userId"/> -->
		<template v-if="sendAgainData!=null">
			<vue-modal-sendagain :user-id="userId" :data="sendAgainData" :users-list="usersList"/>
		</template>
		<template v-if="agreementId!=null">
			<vue-modal-deleteagree :agree-id="agreementId" :agree-text="agreementText" :key="agreementId"/>
		</template>
	</div>
</template>

<script>
	// import Methods from './Methods.vue';
	export default {
		// components: {
		// 	Methods
		// },
		props: {
			userId: String,
		},
		data() {
			return {
				responsesList: [],
				spinOff: true,
				usersList: [],
				documentId: null,
				sendAgainData: null,
				agreementId: null,
				agreementText: null,
			}
		},
		mounted() {
			axios.post('api/getagrresponsesbyuserid', {id: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						this.responsesList = response.data.result;
						this.spinOff = false;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
			axios.post('api/getuserslist', {id: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						this.usersList = response.data.result;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
		},
		methods: {
			getDocId: function(data) {
				this.documentId = data.docId;
			},
			getAgreeId: function(data) {
				this.agreementId = data.agreeId;
				this.agreementText = data.agreeText;
			},
			getSendAgainData: function(data) {
				// console.log(data.data);
				this.sendAgainData = data.data;
			},
		}
	}
</script>