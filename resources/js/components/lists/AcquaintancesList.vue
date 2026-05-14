<template>
	<div class="container">
		<h2>
			Документы на ознакомление для пользователя <span class="font-bold">{{ user }}</span>
		</h2>
		<br/>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header font-bold font-up d-flex justify-content-between align-items-center" v-if="selectedTitle==1">
						<div>
							Все заявки на ознакомление
						</div>
						<div class="selected_title cursor-point" @click="toggleTitle(2)">
							Непросмотренные
						</div>
					</div>
					<div class="card-header font-bold font-up d-flex justify-content-between align-items-center" v-else-if="selectedTitle==2">
						<div class="selected_title cursor-point" @click="toggleTitle(1)">
							Все заявки на ознакомление
						</div>
						<div>
							Непросмотренные
						</div>
					</div>
					<div class="card-body table_scroll_998_y">
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th scope="col">
										Документ
									</th>
									<th class="ta-center" scope="col">
										Тип документа
									</th>
									<th class="ta-center" scope="col">
										Дата отправки
									</th>
									<th class="ta-center" scope="col">
										Отправлен кем
									</th>
									<th class="ta-right" scope="col">
										Статус
									</th>
								</tr>
							</thead>
							<template v-if="$root.spinOffAcqs===true">
								<tbody>
									<tr>
										<td colspan="100%" class="ta-center mt-4">
											<vue-spinner/>
										</td>
									</tr>
								</tbody>
							</template>
							<template v-else-if="$root.spinOffAcqs===false">
								<tbody v-if="$root.acquaintancesList.length>0">
									<template v-for="item in $root.acquaintancesList">
										<vue-item-acquaintanceslist :data="item"/>
									</template>
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
	</div>
</template>
<script>
	export default {
		props: {
			userId: String,
			surname: String,
			firstname: String,
			patronymic: String,
		},
		data() {
			return {
				selectedTitle: 1,
				list: [],
				// spinOff: true,
				user: '',
			}
		},
		created(){
			if (this.$route.query.type) {
				this.selectedTitle = this.$route.query.type;
			} else {
				this.$router.push({name: this.$route.name, query: { type: this.selectedTitle }}).catch(err => {});
			}
			this.toggleTitle(this.$route.query.type);
			this.user = this.$root.makeFio(this.surname, this.firstname, this.patronymic);
		},
		methods: {
			toggleTitle: function(n = 1) {
				this.$router.push({name: this.$route.name, query: { type: n }}).catch(err => {});
				if (n == 1) {
					this.selectedTitle = n;
					this.$root.spinOffAcqs = true;
					this.$root.acquaintancesList = [];
					this.$root.getAcquaintances(this.userId);
				} else if (n == 2) {
					this.selectedTitle = n;
					this.$root.spinOffAcqs = true;
					this.$root.acquaintancesList = [];
					this.$root.getAcquaintances(this.userId, 1);
				}
			},
			getDocId: function(data) {
				this.documentId = data.docId;
			},
            docLink: function(id) {
				window.location.href = '/document-'+id;
			},
		}
	}
</script>