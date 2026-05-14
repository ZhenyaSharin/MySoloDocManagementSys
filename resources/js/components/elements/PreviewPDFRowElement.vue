<template>
	<div>
		<span class="font-bold">{{ title }}:&nbsp;&nbsp;</span><span v-if="data!=null">{{ data }}</span><span v-else>-Не указано</span>
	</div>
</template>
<script>
	export default {
		props: {
			title: {
				type: String,
				default: null,
			},
			data: {
				type: String,
				default: null,
			},
		},
	}
</script>