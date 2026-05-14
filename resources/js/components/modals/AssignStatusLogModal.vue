<template>
	<div class="modal fade" id="assignStatusLogModal" tabindex="-1" role="dialog" aria-labelledby="assignStatusLogTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="assignStatusLogTitle">
						Таблица изменений статуса поручения
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
<!-- 				<pre>
					{{ data }}
				</pre> -->
				<div class="modal-body d-flex justify-content-center align-items-center table_scroll_581_y">
					<table class="table table-striped">
						<thead class="thead-dark">
							<tr>
								<th scope="col" width="200px">Дата изменения</th>
								<th scope="col" class="ta-center">Статус</th>
								<th scope="col" class="ta-right">Комментарий</th>
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
							<tbody v-if="data.length>0">
								<tr v-for="item in data">
									<template v-if="item.removed!=null">
										<td class="ta-center">
											<vue-elem-timestamp :date-time="item.removed"/>
										</td>
									</template>
									<template v-else>
										<td v-if="item.updated_at!=null">
											<vue-elem-timestamp :date-time="item.updated_at"/>&nbsp;(изм.)
										</td>
										<td v-else>
											<vue-elem-timestamp :date-time="item.created_at"/>
										</td>
									</template>
									<template v-if="item.status.title!=null">
										<td v-if="item.assignmentstatusId==6" class="status_considering ta-center">
											{{ item.status.title }}
										</td>
										<td v-else-if="item.assignmentstatusId==7"  class="status_considering ta-center">
											{{ item.status.title }}
										</td>
										<td v-else-if="item.assignmentstatusId==8" class="status_refused ta-center">
											{{ item.status.title }}
										</td>
										<td v-else-if="item.assignmentstatusId==9" class="status_approved ta-center">
											{{ item.status.title }}
										</td>
										<td v-else-if="item.assignmentstatusId==10" class="status_refused ta-center">
											{{ item.status.title }}
										</td>
									</template>
									<template v-else>
										<td>
											-Не указано-
										</td>
									</template>
									<template v-if="item.note!=null">
										<td class="ta-right p-1">
											{{ item.note }}
										</td>
									</template>
									<template v-else>
										<td class="ta-right">
											...
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
			data: Array,
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