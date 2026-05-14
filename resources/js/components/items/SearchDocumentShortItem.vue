<template>
	<tr class="cursor-point" @click="$root.docLink(data.id)">
		<td>
			{{ data.id }}
		</td>
		<td>
			<vue-td-formattedtitle :text="data.description" :length="20"/>
		</td>
		<td class="ta-center">
			<span v-if="data.orderNum!=null">
				{{ data.orderNum }}
			</span>
			<span v-else>
				...
			</span>
		</td>
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