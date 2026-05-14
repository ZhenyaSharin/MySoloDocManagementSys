<template>
	<tr class="cursor-point" @click="$root.docLink(data.agreement.documentId)">
		<td scope="col">
			<vue-td-formattedtitle :text="data.document.description" :length="50"/>
		</td>
		<td class="ta-center ws-nowrap" scope="col">
			{{ $root.makeFio(data.author.surname, data.author.firstname, data.author.patronymic) }}
		</td>
		<td class="ta-center" scope="col">
			{{ data.documentType.title }}
		</td>
		<td class="ta-center timestamp_font" scope="col">
			<vue-elem-timestamp :date-time="data.created_at"/>
		</td>
		<td class="ta-center timestamp_font" scope="col">
			<vue-elem-timestamp :date-time="data.updated_at"/>
		</td>
		<td scope="col" v-if="data.approved_at!=null" class="ta-center status_approved">
			Согласовано
		</td>
		<td scope="col" v-else-if="data.refused_at!=null" class="ta-center status_refused">
			Отказано
		</td>
		<td scope="col" v-if="data.documentStatus.docstatusId==1" class="ta-right status_considering">
			{{ data.documentStatus.docStatusTitle }}
		</td>
		<td scope="col" v-else-if="data.documentStatus.docstatusId==2" class="ta-right status_refused">
			{{ data.documentStatus.docStatusTitle }}
		</td>
		<td scope="col" v-else-if="data.documentStatus.docstatusId==3" class="ta-right status_approved">
			<template v-if="data.created_at===data.updated_at">
				Подписан
			</template>
			<template v-else>
				{{ data.documentStatus.docStatusTitle }}
			</template>
		</td>
		<td scope="col" v-else-if="data.documentStatus.docstatusId==4" class="ta-right status_inarchive">
			{{ data.documentStatus.docStatusTitle }}
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