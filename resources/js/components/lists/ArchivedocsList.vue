<template>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header font-bold font-up d-flex justify-content-between align-items-center">
						<div>
							Архив
						</div>
					</div>
					<div class="card-body table_scroll_998_y">
						<table class="table table-hover">
							<thead class="thead-dark">
								<tr>
									<th scope="col">
										Документ
									</th>
									<th class="ta-center" scope="col">
										Номер док-та
									</th>
									<th class="ta-center" scope="col">
										Тип
									</th>
									<th class="ta-center" scope="col">
									<!-- <th class="ta-center timestamp_font p-1" scope="col"> -->
										<!-- <div> -->
											Дата документа
										<!-- </div> -->
<!-- 										<div>
											Дата создания
										</div> -->
									</th>
									<th class="ta-center" scope="col">
										Основание
									</th>
									<th class="ta-right" scope="col">
										Статус
									</th>
								</tr>
							</thead>
							<template v-if="$root.spinOffSingle===true">
								<tbody>
									<tr>
										<td colspan="100%" class="ta-center mt-4">
											<vue-spinner/>
										</td>
									</tr>
								</tbody>
							</template>
							<template v-else-if="$root.spinOffSingle===false">
								<tbody v-if="$root.docsListAll.length>0">
									<template v-for="(item, index) in $root.docsListAll">
										<vue-item-documentitem v-if="((index >= $root.docsListFirstAll - 1) && (index <= $root.docsListLastAll - 1))" :data="item"/>
									</template>
								</tbody>
								<tbody v-else>
									<tr class="tr-greyplug">
										<th colspan="100%" class="ta-center font-up">
											Ничего не найдено
										</th>
									</tr>
								</tbody>
							</template>
						</table>
						<vue-template-choosepagination/>
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
		},
		mounted() {
			this.$root.selectedTitle = 2;
			this.$root.getListByDocStatusId(4);
		},
	}
</script>