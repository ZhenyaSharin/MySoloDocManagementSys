<template>
	<div class="container">
		<div class="row">
			<div class="col-md-12 d-flex justify-content-between align-items-center">
				<div>
					<h1>Общая аналитика</h1>
				</div>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-md-5">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th colspan="100%" scope="col" class="font-up card-header_custom">
								Общая аналитика по системе:
							</th>
						</tr>
					</thead>
					<tbody>
						<tr class="cursor-point table-active" @click="link('documents', 2)">
							<th scope="col">
								Карточек документов
							</th>
							<td scope="col" class="ta-right font-bold">
								{{ docsList.length }}
							</td>
						</tr>
						<tr class="cursor-point">
							<td scope="col">
								Документов на рассмотрении
							</td>
							<td scope="col" class="ta-right font-bold">
								{{ docsConsidCount }}
							</td>
						</tr>
						<tr class="cursor-point">
							<td scope="col">
								Согласованных/подписанных документов
							</td>
							<td scope="col" class="ta-right status_approved">
								{{ docsApprovedCount }}
							</td>
						</tr>
						<tr class="cursor-point">
							<td scope="col">
								Документов в архиве
							</td>
							<td scope="col" class="ta-right status_refused">
								{{ docsArchiveCount }}
							</td>
						</tr>
						<tr class="cursor-point table-active" @click="link('assignments', 3)">
							<th scope="col">
								Поручений
							</th>
							<td scope="col" class="ta-right font-bold">
								{{ assignsList.length }}
							</td>
						</tr>
						<tr class="cursor-point">
							<td scope="col">
								Просроченных поручений
							</td>
							<td scope="col" class="ta-right status_refused">
								{{ assignsOverdue }}
							</td>
						</tr>
						<tr class="cursor-point">
							<td scope="col">
								Актуальных поручений
							</td>
							<td scope="col" class="ta-right status_approved">
								{{ assignsActual }}
							</td>
						</tr>
						<tr class="cursor-point">
							<td scope="col">
								Исполненных поручений
							</td>
							<td scope="col" class="ta-right font-bold">
								{{ assignsCompleteCount }}
							</td>
						</tr>
						<tr class="cursor-point table-active">
							<th scope="col">
								Пользователей
							</th>
							<td scope="col" class="ta-right font-bold">
								{{ usersList.length }}
							</td>
						</tr>
						<tr class="cursor-point">
							<td scope="col">
								Активных пользователей
							</td>
							<td scope="col" class="ta-right status_approved">
								{{ usersCount }}
							</td>
						</tr>
						<tr class="cursor-point">
							<td scope="col">
								Заблокированных пользователей
							</td>
							<td scope="col" class="ta-right status_refused">
								{{ usersBlockedCount }}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-7 table_scroll_y">
				<table class="table table-hover">
					<thead class="thead-dark card-header_custom">
						<tr>
							<th width="55%" scope="col" class="font-up">
								Пользователь
							</th>
							<th width="25%" class="font-up ta-center ws-nowrap">
								Карт.док-тов
							</th>
							<th width="20%" scope="col" class="font-up ta-right">
								Поручений
							</th>
						</tr>
					</thead>
					<tbody>
						<template v-for="item in usersList">
							<tr v-if="item.removed==null" class="cursor-point" @click="linkUser(item.login)">
								<td scope="col">
									{{ item.surname }} {{ item.firstname }} {{ item.patronymic }}
								</td>
								<td scope="col" class="ta-center">
									{{ item.docs.length }}
								</td>
								<td scope="col" class="ta-center">
									{{ item.assigns.length }}
								</td>
							</tr>
						</template>
					</tbody>
				</table>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-md-12 table_scroll_998_y table_scroll_y">
				<table class="table">
					<thead class="thead-dark card-header_custom">
						<tr>
							<th width="40%" scope="col" class="font-up">
								Отдел
							</th>
							<th width="10%" class="ta-center">
								Пользователей
							</th>
							<th width="10%" class="ta-center ws-nowrap" title="Карточек документов">
								К. док-тов
							</th>
							<th width="10%" class="ta-center" title="Поручений">
								Поручений
							</th>
							<th width="30%" class="ta-right font-up" scope="col">
								Добавлен в систему
							</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="item in departments">
							<th scope="col">
								{{ item.title }}
							</th>
							<td class="ta-center">
								{{ item.users.length }}
							</td>
							<td class="ta-center" v-if="item.docs.length>0">
								<a href="/documents?type=2">
									{{ item.docs.length }}
								</a>
							</td>
							<td class="ta-center" v-else>
								0
							</td>
							<td class="ta-center" v-if="item.docs.length>0">
								<a href="/assignments?type=3">
									{{ item.assigns.length }}
								</a>
							</td>
							<td class="ta-center" v-else>
								0
							</td>
							<td scope="col" class="ta-right">
								<vue-elem-timestamp :date-time="item.created_at" style="font-size: 0.95em;"/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				departments: Array,
				docsList: Array,
				assignsList: Array,
				docsConsidCount: 0,
				docsApprovedCount: 0,
				docsArchiveCount: 0,
				assignsCompleteCount: 0,
				usersList: Array,
				usersBlockedCount: 0,
				usersCount: 0,
				assignsOverdue: 0,
				assignsActual: 0,
				now: new Date(),
			}
		},
		created() {
			this.getDocs();
			this.getAssigns();
			this.getUsers();
			axios.post('api/departmentsuserslist')
				.then(response => {
					if (response.data.error == 0) {
						this.departments = response.data.result;
						// console.log(this.departments[0].title);
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
		},
		methods: {
			getDocs: function() {
				// this.spinOffDocs = true;
				axios.post('api/analyticsdocslist', {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							// this.spinOffDocs = false;
							this.docsList = response.data.result;
							// console.log(this.docsList);
							this.docsList.forEach(item => {
								if (item.status.docstatusId == 1) {
									this.docsConsidCount++;
								} else if (item.status.docstatusId == 3) {
									this.docsApprovedCount++;
								} else if (item.status.docstatusId == 4) {
									this.docsArchiveCount++;
								}
							});
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
			getAssigns: function() {
				axios.post('api/analyticsassignslist', {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							// this.spinOffDocs = false;
							this.assignsList = response.data.result;
							// console.log(this.assignsList);
							this.assignsList.forEach(item => {
								if (item.status.id == 9) {
									this.assignsCompleteCount++;
								}
							});
							this.assignsOverdue = this.checkStatus(this.assignsList, 1);
							this.assignsActual = this.checkStatus(this.assignsList, 2);
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
			getUsers: function() {
				axios.post('api/usersanalyticslist', {
					headers: {
						"Content-Type": "application/json"
					}
				}).then((response) => {
                    this.usersList = response.data.result;
                    this.usersList.forEach(item => {
                    	if (item.removed != null) {
                    		this.usersBlockedCount++;
                    	} else {
                    		this.usersCount++;
                    	}
                    });
                });
			},
			checkStatus: function(array, type) {
				let arr = array;
				let n = 0;
				if (arr.length > 0) {
					arr.forEach(item => {
						if ((item.status.id == 6) || (item.status.id == 7)) {
							let dt = new Date(item.deadline[0].deadline);
							let delta = (dt - this.now)/(24*3600*1000);
							if (type === 1) {
								if ((dt - this.now) < 0) {
									n++;
								}
							} else if (type === 2) {
								if ((dt - this.now) >= 0) {
									n++;
								}
							}
						}
					});
				}
				return n;
			},
			link: function(page, type) {
				window.location.href = '/'+ page + '?type='+ type;
			},
			linkUser: function(login) {
				window.location.href = '/id_'+ login;
			},
		},
	}
</script>