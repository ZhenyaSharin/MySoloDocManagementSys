<template>
	<div class="card-body table_scroll_998_y">
		<table class="table th-vert-middle table-hover">
			<!-- <vue-thead-letters :type-id="typeId" :user-id="userId"/> -->
			<vue-thead-docslist :type-id="typeId" :user-id="userId" :is-diruser="isDiruser"  :title='title' :title-num='titleNum'/>
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
				<vue-tbody-docsbytype/>
			</template>
		</table>
		<vue-template-choosepagination/>
	</div>
</template>
<script>
	export default {
		props: {
			userId: Number,
			typeId: Number,
			isDiruser: {
				type: Boolean,
				default: true,
			},
			title: {
				type: String,
				default: 'Дата документа',
			},
			titleNum: {
				type: String,
				default: 'Номер док-та',
			},
			isType: {
				type: Boolean,
				default: false,
			},
			isStatus: {
				type: Boolean,
				default: false,
			},
		},
	}
</script>