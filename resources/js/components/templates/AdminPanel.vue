<template>
	<div>
		<div class="d-flex justify-content-between align-items-center">
			<h1>
				Привет, Админ
			</h1>
			<a class="font-bold" href="/blog">
				Информационная лента
			</a>
		</div>
		<br/>
		<div class="row">
			<vue-template-newuser/>
		</div>
		<br/>
<!-- 		<button class="btn btn-primary no-round" @click="refresh()">
			Обновить кэш
		</button> -->
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header_custom font-bold d-flex justify-content-between">
						<div class="font-up">
							Список пользователей
						</div>
						<div class="d-flex align-items-center mr-1">
							<div v-if="modeUser==1">
								Активные
							</div>
							<div v-else class="selected_title cursor-point" @click="usersList(1)">
								Активные
							</div>
							<div>
								&nbsp;&nbsp;<i class="smalldot fas fa-ellipsis-h"/>&nbsp;&nbsp;
							</div>
							<div v-if="modeUser==2">
								Заблокированные
							</div>
							<div v-else class="selected_title cursor-point" @click="usersList(2)">
								Заблокированные
							</div>
							<div>
								&nbsp;&nbsp;<i class="smalldot fas fa-ellipsis-h"/>&nbsp;&nbsp;
							</div>
							<div v-if="modeUser==3">
								Все
							</div>
							<div v-else class="selected_title cursor-point" @click="usersList(3)">
								Все
							</div>
						</div>
					</div>
					<div class="card-body table_scroll_998_y">
						<table class="table table-hover">
							<thead>
								<tr>
									<th scope="col">
										Id
									</th>
									<th class="ta-center" scope="col">
										ФИО
									</th>
									<th class="ta-center" scope="col">
										Дата и время добавления
									</th>
									<th class="ta-center" scope="col">
										Посл. изменение
									</th>
									<th class="ta-center" scope="col">
										Подразделение
									</th>
									<th class="ta-center" scope="col">
										Роль
									</th>
									<th class="ta-right" scope="col">
										Статус
									</th>
								</tr>
							</thead>
							<tbody v-if="spinOff==true">
								<tr>
									<td colspan="100%" class="ta-center mt-4">
										<vue-spinner/>
									</td>
								</tr>
							</tbody>
							<tbody v-else-if="spinOff==false">
								<template v-if="users.length>0">
									<template v-for="(item, index) in users">
										<vue-item-adminuser :data="item" @click.native="getCurrentInfo(item)"/>
									</template>
								</template>
								<template v-else>
									<tr>
										<th colspan="100%" class="ta-center font-up tr-greyplug">
											Пользователи ещё не созданы
										</th>
									</tr>
								</template>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<template v-if="currentItem!=null">
				<vue-modal-userprofile :data="currentItem" :departments="departments" :key="currentItem.id"/>
			</template>
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
				users: [],
				spinOff: true,
				departments: [],
				currentItem: null,
                newPasswordErr: false,
                newPasswordOk: false,
                newPassword: '',
                modeUser: null,
                roles: [],
			}
		},
		created() {
			if (this.$route.query.order != null) {
                this.modeUser = this.$route.query.order;
            } else {
            	this.$router.push({name: this.$route.name, query: { order: 1 }})
					.catch(err => {});
            };
			this.usersList(this.$route.query.order);
			axios.post('api/getdepartments')
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

			axios.post('api/getroleslist')
				.then(response => {
					if (response.data.error == 0) {
						this.roles = response.data.result;
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
			getCurrentInfo: function(item) {
				this.refreshPass();
                this.currentItem = {
                	id: item.id,
                    login: item.login,
                    email: item.email,
                    surname: item.surname,
                    firstname: item.firstname,
                    patronymic: item.patronymic,
                    department: item.department,
                    removed: item.removed,
                };
                this.currentItem.roleId = (item.role != false) ? item.role.id : 2;
             },
			refresh: function() {
				axios.get('/refresh')
					.then((response) => {
						console.log(response.data);
						alert('Кэш обновлён');
	                });
			},
			refreshPass: function() {
				this.newPasswordErr = false;
                this.newPasswordOk = false;
                this.newPassword = '';
			}, 
			usersList: function(n = 1) {
				this.modeUser = n;
				this.$router.push({name: this.$route.name, query: { order: n }})
					.catch(err => {});
				this.spinOff = true;
				let list = [];
				this.users = [];
				axios.post('api/getuserslist', {id: 3}, {
					headers: {
						"Content-Type": "application/json"
					}
				}).then((response) => {
                    list = response.data.result;
                    console.log(list);
                    if (n === 1) {
						list.forEach(item => {
							if (item.removed == null) {
								this.users.push(item);
							}
						});
					} else if (n === 2) {
						list.forEach(item => {
							if (item.removed != null) {
								this.users.push(item);
							}
						});
					} else if (n === 3) {
						this.users = list;
					}	
					this.spinOff = false;
                });
			},
		}
	}
</script>