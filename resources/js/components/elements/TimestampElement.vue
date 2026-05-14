<template>
	<span v-if="dateTime!=null" class="timestamp_font ws-nowrap">
		{{ $root.frmtDate(dateTime, short) }}
	</span>
	<span v-else>
		...
	</span>
</template>
<script>
	export default {
		props: {
			dateTime: String,
			short: {
				type: Boolean,
				default: false,
			},
		},
	}
</script>