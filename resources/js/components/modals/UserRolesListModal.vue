<template>
		<div class="modal fade" id="userRolesLogModal" tabindex="-1" role="dialog" aria-labelledby="userRolesLogTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="userRolesLogTitle">
						Таблица изменений роли
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body d-flex justify-content-center align-items-center table_scroll_581_y">
					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col">
									Роль
								</th>
								<th class="ta-right" scope="col">
									Дата назначения
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
							<tbody v-if="arr.length>0">
								<tr v-for="item in arr">
									<td class="font-bold">
										{{ item.role.title }}
									</td>
									<template v-if="item.created_at">
										<td class="ta-right">
											<vue-elem-timestamp :date-time="item.created_at"/>
										</td>
									</template>
									<template class="ta-right" v-else>
										<td>
											-Не указано-
										</td>
									</template>
								</tr>
							</tbody>
							<tbody v-else>
								<tr>
									<td class="font-bold">
										{{ $root.roleData.role.title }}
									</td>
									<td class="ta-right">
										<vue-elem-timestamp :date-time="$parent.data.created_at"/>
									</td>
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
			arr: Array,
		},
		data() {
			return {
				spinOff: true,
			}
		},
		mounted() {
			this.spinOff = false;
		}
	}
</script>