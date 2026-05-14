<template>
	<div class="container">
		<div class="row alert alert-danger d-flex justify-content-between align-items-center" v-if="data.removed!=null">
	    	<div>
	    		<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Аккаунт заблокирован...
	    	</div>
		</div>
		<div class="row">
			<div class="col-md-12 d-flex justify-content-between align-items-center">
				<div class="d-flex flex-column flex-sm-row mb-1">
					<h1>
						<span class="ws-nowrap">
							{{ $root.makeFio(data.surname, data.firstname, data.patronymic) }}&nbsp;
						</span>
					</h1>
					<div>
						<span v-if="id==userId" class="greytxt font-bold ws-nowrap" style="font-size: 24px;">
							(Это Вы...)
						</span>
					</div>
				</div>
				<br/>
			</div>
		</div>
		<div class="row">
			<div class="col-md-7">
				<div class="custom-h4 mb-2 mb-md-3">
					Информация о пользователе:
				</div>
				<div class="mb-1">
					Ф.И.О.&nbsp;: 
						<span class="font-bold">
							{{ data.surname }}&nbsp;{{ data.firstname }}&nbsp;{{ data.patronymic }}
						</span>
				</div>
				<div class="d-flex justify-content-between mb-1">
					<div>
						Роль&nbsp;:&nbsp;
						<span v-if="userRoles.length>0" class="font-bold">
							{{ userRoles[0].role.title }}
						</span>
						<span v-else class="font-bold">
							Пользователь (огр.)
						</span>
					</div>
					<a v-if="id==userId&&data.removed==null" href="#" class="greytxt font-bold" data-toggle="modal" data-target="#userRolesLogModal">
						Лист изменений
					</a>
				</div>
				<div class="mb-1">
					<div>
						Email&nbsp;: 
						<span class="font-bold">
							{{ data.email }}
						</span>
					</div>
				</div>
				<div class="mb-2">
					Дата и время добавления в систему :&nbsp;
					<span class="font-bold">
						<vue-elem-timestamp :date-time="data.created_at"/>
					</span>
				</div>
				<div>
					<div class="row">
						<div class="col-md-12">
							<table class="table font-tble">
								<thead class="thead-dark ">
									<tr>
										<th width="60%" scope="col" class="font-up">
											Отдел
										</th>
										<th width="40%" scope="col" class="font-up  ta-right">
											Дата включения
										</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="item in data.department">
										<th scope="col">
											{{ item.title }}
										</th>
										<td scope="col" class="ta-right">
											<vue-elem-timestamp :date-time="item.created_at"/>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div v-if="id==userId&&data.removed==null" class="col-md-5">
				<div class="row">
					<vue-alert-inwork/>
					<div class="col-md-12 d-flex justify-content-end">
						<button class="btn btn btn-success no-round font-up font-bold py-3 box-shad_black" data-toggle="modal" data-target="#userMailSettingsModal">
							<i class="fas fa-cogs fa-lg"/>&nbsp;&nbsp;Настройки уведомлений
						</button>	
					</div>
				</div>
			</div>
		</div>
		<br/>
		<br/>
		<div class="row">
			<div class="col-md-12">
				<div class="back_white p-3 p-md-4">
					<div class="custom-h3">
						Статистика по присланным поручениям
					</div>
					<br>
					<table class="table table-hover">
						<tbody v-if="executorList.length>0">
							<tr @click="assignListLink(2)" class="cursor-point">
								<th scope="row">
									Всего поручений:
								</th>
								<td class="ta-right num_larger font-bold">
									{{ checkStatus(executorList, 0) }}
								</td>	
							</tr>
							<tr @click="assignListLink(2)" class="cursor-point">
								<th scope="row">
									Активных поручений:
								</th>
								<td class="ta-right num_larger font-bold status_approved">
									{{ checkStatus(executorList, 2) }}
								</td>
							</tr>
							<tr @click="assignListLink(2)" class="cursor-point">
								<th scope="row">
									Просроченных поручений:
								</th>
								<td class="ta-right num_larger font-bold status_refused">
									{{ checkStatus(executorList, 1) }}
								</td>
							</tr>
						</tbody>
						<tbody v-else>
							<tr>
								<td colspan="100%" class="ta-center mt-4">
									<vue-spinner/>
								</td>
							</tr>
						</tbody>
					</table>	
				</div>	
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-md-12">
				<div class="back_white p-3 p-md-4">
					<div class="custom-h3">
						Статистика по отправленным поручениям
					</div>
					<br>
					<table class="table table-hover">
						<tbody v-if="authorList.length>0">
							<tr @click="assignListLink(1)" class="cursor-point">
								<th scope="row">	
									Отправленных поручений
								</th>
								<td class="ta-right num_larger font-bold">
									{{ checkStatus(authorList, 0) }}
								</td>
							</tr>
							<tr @click="assignListLink(1)" class="cursor-point">
								<th scope="row">
									Невыполненных поручений
								</th>
								<td class="ta-right num_larger font-bold status_refused">
									{{ checkStatus(authorList, 1) }}
								</td>
							</tr>
						</tbody>
						<tbody v-else>
							<tr>
								<td colspan="100%" class="ta-center mt-4">
									<vue-spinner/>
								</td>
							</tr>
						</tbody>
					</table>	
				</div>	
			</div>
		</div>
		<vue-modal-usermailsettings v-if="id==userId&&data.removed==null" :settings="mailSettings"/>
		<vue-modal-userroleslist v-if="id==userId&&data.removed==null" :arr="userRoles"/>
	</div>
</template>
<script>
	export default {
		props: {
			login: String,
			userId: Number,
		},
		data() {
			return {
				data: Object,
				executorList: Array,
				authorList: Array,
				now: new Date(),
				id: null,
				mailSettings: Array,
				userRoles: [],
			}
		},
		created() {
			this.$root.checkRole(this.userId);
			axios.post('api/getuserbysmth', {
					login: this.login, 
					info: 1
				}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						this.data = response.data.result;
						console.log(this.data);
						this.id = response.data.result.id;
						this.getStatisticsByExecutor();
						this.getStatisticsByAuthor();
						this.getSettings();
						this.rolesList();
					} else if (response.data.error == 2) {
						window.location.href = '/pagenotfound';
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					// alert('Ошибка получения данных');
					console.log(error);
				});
		},
		methods: {
			assignListLink: function(type = 1) {
				// this.$router.href({ path: '/document-'+id })
				window.location.href = '/assignments?type='+ type;
			},
			getStatisticsByExecutor: function() {
				axios.post('api/assignexecutors', {executorId: this.id}, {
						headers: {
							"Content-Type": "application/json"
						}
					})
					.then(response => {	
						if (response.data.error == 0) {
							this.executorList = response.data.result;
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
			getStatisticsByAuthor: function() {
				axios.post('api/assignmentsbyauthor', {authorId: this.id}, {
						headers: {
							"Content-Type": "application/json"
						}
					})
					.then(response => {	
						if (response.data.error == 0) {
							this.authorList = response.data.result;
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
			checkStatus: function(array, type) {
				let n = 0;
				if (type === 0) {
					n = array.length;
				} else {
					// console.log(array);
					array.forEach(item => {
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
			getSettings: function() {
				axios.post('api/getmailsettings', {userId: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						this.mailSettings = response.data.result;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					// alert('Ошибка получения данных');
					console.log(error);
				});
			},
			rolesList: function() {
				axios.post('api/checkrole', {
					userId: this.data.id,
				}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {	
						if (response.data.error == 0) {
							this.userRoles = response.data.result;
							console.log(this.userRoles);
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						// alert('Ошибка получения данных');
						console.log(error);
					});
			}
		}
	}
</script>