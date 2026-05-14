<template>
	<tr v-if="data.viewed_at!=null" class="cursor-point" @click="$root.docLink(data.agreement.documentId)">
		<td>
			<vue-td-formattedtitle :text="data.documentDescription" :length="50"/>
		</td>
		<td class="ta-center">
			{{ data.documentType }}
		</td>
		<vue-td-docstatus :date="data.created_at" :status="data.status"/>
		<td class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<template v-if="data.agreement.deadline!=null">
			<vue-td-dateleft :status="data.status.docstatusId" :date="data.agreement.deadline"/>
		</template>
		<template v-else>
			<td class="ta-center">
				...
			</td>
		</template>
		<td class="ta-right ws-nowrap">
			{{ $root.makeFio(data.author.surname, data.author.firstname, data.author.patronymic) }}
		</td>
	</tr>
	<tr v-else class="cursor-point notviewed" @click="$root.docLink(data.agreement.documentId)">
		<td class="font-bold">
			<vue-td-formattedtitle :text="data.documentDescription" :length="50"/>
		</td>
		<td class="ta-center">
			{{ data.documentType }}
		</td>
		<vue-td-docstatus :date="data.created_at" :status="data.status"/>
		<td class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<template v-if="data.agreement.deadline!=null">
			<vue-td-dateleft :status="data.status.docstatusId" :date="data.agreement.deadline"/>
		</template>
		<template v-else>
			<td class="ta-center">
				...
			</td>
		</template>
		<td class="ta-right ws-nowrap">
			{{ $root.makeFio(data.author.surname, data.author.firstname, data.author.patronymic) }}
		</td>
	</tr>
</template>
<script>
	export default {
		props: {
			data: Object,
			userId: String,
		},
	}
</script>