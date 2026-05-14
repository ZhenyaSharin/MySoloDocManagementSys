<template>
	<div class="modal fade" id="userMailSettingsModal" tabindex="-1" role="dialog" aria-labelledby="userMailSettingsModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title" id="userMailSettingsModalTitle">
						<span class="font-up font-bold">Таблица настройки уведомлений</span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body table_scroll_581_y">
					<table class="table">
						<thead>
							<tr class="table-secondary font-up font-bold">
								<th width="80%" scope="col">
									Опция
								</th>
								<th width="20%" class="ta-center" scope="col"></th>
							</tr>
						</thead>
						<tbody v-if="settings.length>0">
							<template v-for="item in settings">
								<tr>
									<th scope="row">
										{{ item.title }}
									</th>
									<td v-if="item.user!=false" class="ta-center">
										<input v-if="item.user.status==true" class="cursor-point" type="checkbox" aria-label="Checkbox for following text input" checked @click="updateMailSetting(item.user.id, 0)" title="Нажмите чтобы отключить настройку">
										<input v-else-if="item.user.status==false" class="cursor-point" type="checkbox" aria-label="Checkbox for following text input" @click="updateMailSetting(item.user.id, 1)" title="Нажмите чтобы включить настройку">
									</td>
									<td v-else class="ta-center redrow" title="Настройка не задана">
										<input class="cursor-point" type="checkbox" aria-label="Checkbox for following text input" title="Нажмите чтобы добавить настройку" @click="addMailSetting(item.id)">
									</td>
								</tr>
							</template>
						</tbody>
						<tbody v-else>
							<tr class="tr-greyplug">
								<th colspan="100%" class="ta-center font-up">
									<!-- <button class="btn btn-success no-round font-bold font-up" @click="addAllsettings()">
										Задать настройки
									</button> -->
								</th>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			settings: Array,
		},
		data() {
			return {
				// 
			}
		},
		// created() {
		// 	console.log(this.settings);
		// },
		methods: {
			addAllsettings: function() {
				axios.post('api/addallmailsettings', {id: this.$parent.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						console.log('Выполнено');
						this.$router.go();
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					// alert('Ошибка получения данных');
					console.log(error);
				});
			},
			updateMailSetting: function(settingId, status) {
				axios.post('api/updatemailsettingstatus', {id: settingId, status: status}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						console.log('Выполнено');
						this.$parent.getSettings();
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					// alert('Ошибка получения данных');
					console.log(error);
				});
			},
			addMailSetting: function(settingId) {
				axios.post('api/addmailsettinguser', {userId: this.$parent.id, settingId: settingId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						console.log('Выполнено');
						this.$parent.getSettings();
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					// alert('Ошибка получения данных');
					console.log(error);
				});
			},
		}
	}
</script>