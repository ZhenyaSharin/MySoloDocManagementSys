<template>
	<tr class="cursor-point" @click="$root.assignLink(data.id)">
		<td>
			{{ data.id }}
		</td>
		<td class="px-1">
			<vue-td-formattedtitle :text="data.text" :length="25"/>
		</td>
		<vue-td-dateleft :status="data.status.id" :date="data.deadline[0].deadline"/>
		<td class="ta-right">
			{{ data.type.title }}
		</td>
	</tr>
</template>
<script>
	export default {
		props: {
			data: Object,
		},
	}
</script>