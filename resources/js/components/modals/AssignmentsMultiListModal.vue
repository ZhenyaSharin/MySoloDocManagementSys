<template>
	<div class="modal fade" id="assignmentsMultiListModal" tabindex="-1" role="dialog" aria-labelledby="assignmentsMultiListTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title" id="assignmentsMultiListTitle">
						<span class="font-up font-bold">
							Список поручений
						</span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body p-1">
					<div class="col-md-12 table_scroll_998_y p-0">
						<table class="table">
							<thead>
								<tr>
									<th scope="col">
										Поручение
									</th>
									<th class="ta-center" scope="col">
										Тип
									</th>
									<th class="ta-center" scope="col">
										Дата создания
									</th>
									<th class="ta-center" scope="col">
										Основание
									</th>
									<th v-if="type=='author'" class="ta-center" scope="col">
										Исполнитель
									</th>
									<th v-else-if="type=='executor'" class="ta-center" scope="col">
										Автор
									</th>
									<th class="ta-center" scope="col">
										Срок исполнения
									</th>
									<th class="ta-right" scope="col">
										Статус
									</th>
									<th v-if="type=='author'" class="ta-right" scope="col">
										
									</th>
								</tr>
							</thead>
							<tbody v-if="list.length>0">
								<template v-if="type=='executor'">
									<template v-for="item in list">
										<vue-item-assignment-author :data="item" :button="false"/>
									</template>
								</template>
								<template v-else-if="type=='author'">
									<template v-for="item in list">
										<vue-item-assignment-author :get-assign-id="$parent.getAssignId" :data="item"/>
									</template>
								</template>
							</tbody>
							<tbody v-else>
								<tr class="tr-greyplug">
									<th colspan="100%" class="ta-center font-up">
										Список пуст
									</th>
								</tr>
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
			type: String,
		},
	}
</script>