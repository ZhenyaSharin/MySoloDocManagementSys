<template>
	<td v-if="index!=null" class="td-index font-bold timestamp_font">
		{{ index }}
	</td>
</template>
<script>
	export default {
		props: {
			index: {
				type: Number,
				default: null,
			},
		},
	}
</script>