<template>
	<div class="modal fade" id="docStatusLogModal" tabindex="-1" role="dialog" aria-labelledby="docStatusLogTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="docStatusLogTitle">
						Таблица изменений статуса карточки документа
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body d-flex justify-content-center align-items-center table_scroll_581_y">
					<table class="table table-striped">
						<thead>
							<tr>
								<th scope="col">Дата изменения</th>
								<th class="ta-right" scope="col">Статус</th>
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
							<tbody v-if="docData.status.length>0">
								<tr class="cursor-point" v-for="item in docData.status">
									<template v-if="item.removed!=null">
										<td>
											<vue-elem-timestamp :date-time="item.removed"/>
										</td>
									</template>
									<template v-else>
										<td v-if="item.updated_at!=null">
											<vue-elem-timestamp :date-time="item.updated_at"/>
										</td>
										<td v-else>
											<vue-elem-timestamp :date-time="item.created_at"/>
										</td>
									</template>
									<template v-if="item.docStatusTitle!=null">
										<td v-if="item.docstatusId==1" class="status_considering ta-right">
											{{ item.docStatusTitle }}
										</td>
										<td v-else-if="item.docstatusId==2"  class="status_refused ta-right">
											{{ item.docStatusTitle }}
										</td>
										<td v-else-if="item.docstatusId==3" class="status_approved ta-right">
											<template v-if="docData.status.length==1">
												Подписано
											</template>
											<template v-else>
												{{ item.docStatusTitle }}
											</template>
										</td>
										<td v-else-if="item.docstatusId==4" class="status_inarchive ta-right">
											{{ item.docStatusTitle }}
										</td>
										<td v-else-if="item.docstatusId==5" class="status_refused ta-right">
											{{ item.docStatusTitle }}
										</td>
									</template>
									<template v-else>
										<td>
											-Не указано-
										</td>
									</template>
								</tr>
							</tbody>
							<tbody v-else>
								<tr>
									<th colspan="100%" class="ta-center font-up">
										Документы ещё не созданы
									</th>
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
			docData: Object,
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