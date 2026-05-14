<template>
	<div class="modal fade" id="deadlinesLogModal" tabindex="-1" role="dialog" aria-labelledby="deadlinesLogModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title" id="deadlinesLogModalTitle">
						<span class="font-up font-bold">Список переносов срока исполнения</span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body py-4">
					<div class="col-md-12 table_scroll_581_y">
						<table class="table table-striped">
							<thead class="thead-dark">
								<tr>
									<th scope="col">
										Дата запроса/решения
									</th>
									<th scope="col" class="ta-center">
										Запрашиваеваемый срок
									</th>
									<th scope="col" class="ta-right">
										Статус рассмотрения
									</th>
								</tr>
							</thead>
							<tbody>
								<template v-for="(item, index) in list">
									<template v-if="index==list.length - 1">
										<tr>
											<td>
												<vue-elem-timestamp :date-time="item.created_at"/>
											</td>
											<td class="ta-center">
												{{ $root.frmtDate(item.deadline) }}
											</td>
											<td class="ta-right status_considering">
												Создан
											</td>
										</tr>
									</template>
									<template v-else>
										<tr v-if="item.approved_at!=null">
											<td>
												<vue-elem-timestamp :date-time="item.created_at"/>
											</td>
											<td class="ta-center">
												{{ $root.frmtDate(item.deadline) }}
											</td>
											<td class="ta-right status_approved">
												Одобрен
											</td>
										</tr>
										<tr v-else-if="item.refused_at!=null">
											<td>
												<vue-elem-timestamp :date-time="item.created_at"/>
											</td>
											<td class="ta-center">
												{{ $root.frmtDate(item.deadline) }}
											</td>
											<td class="ta-right status_refused">
												Отклонён
											</td>
										</tr>
										<tr v-else>
											<td>
												<vue-elem-timestamp :date-time="item.created_at"/>
											</td>
											<td class="ta-center">
												{{ $root.frmtDate(item.deadline) }}
											</td>
											<td class="ta-right status_considering">
												На рассмотрении
											</td>
										</tr>
									</template>
								</template>
							</tbody>
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
			list: Array,
		}
	}
</script>