<template>
	<td v-if="status.docstatusId==1" class="status_considering timestamp_font ta-right ws-nowrap">
		{{ status.docStatusTitle }}
	</td>
	<td v-else-if="status.docstatusId==2"  class="status_refused timestamp_font ta-right ws-nowrap">
		{{ status.docStatusTitle }}
	</td>
	<td v-else-if="status.docstatusId==3" class="status_approved timestamp_font ta-right ws-nowrap">
		<template v-if="date===status.created_at">
			Подписан
		</template>
		<template v-else>
			{{ status.docStatusTitle }}
		</template>
	</td>
	<td v-else-if="status.docstatusId==4" class="status_inarchive timestamp_font ta-right ws-nowrap">
		{{ status.docStatusTitle }}
	</td>
</template>
<script>
	export default {
		props: {
			status: Object,
			date: String,
		},
	}
</script>