<template>
	<tr v-if="data.seen_at==null" class="cursor-point notviewed" @click="$root.docLink(data.documentId)">
		<td class="font-bold">
			<vue-td-formattedtitle :text="data.document.description" :length="50"/>
		</td>
		<td class="ta-center">
			{{ data.documentType.title }}
		</td>
		<td class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.created_at"/>
		</td>
		<td class="ta-center ws-nowrap">
			{{ $root.makeFio(data.user.surname, data.user.firstname, data.user.patronymic) }}
		</td>
		<template v-if="data.document.typeId==6">
			<td class="ta-right">
				...
			</td>
		</template>
		<template v-else>
			<vue-td-docstatus :date="data.document.created_at" :status="data.status"/>
		</template>
	</tr>
	<tr v-else class="cursor-point" @click="$root.docLink(data.documentId)">
		<td class="font-bold">
			<vue-td-formattedtitle :text="data.document.description" :length="50"/>
		</td>
		<td class="ta-center">
			{{ data.documentType.title }}
		</td>
		<td class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.created_at"/>
		</td>
		<td class="ta-center ws-nowrap">
			{{ $root.makeFio(data.user.surname, data.user.firstname, data.user.patronymic) }}
		</td>
		<template v-if="data.document.typeId==6">
			<td class="ta-right">
				...
			</td>
		</template>
		<template v-else>
			<vue-td-docstatus :date="data.document.created_at" :status="data.status"/>
		</template>
	</tr>
</template>
<script>
	export default {
		props: {
			data: Object,
		},
		// data() {
		// 	return {

		// 	}
		// }
	}
</script>