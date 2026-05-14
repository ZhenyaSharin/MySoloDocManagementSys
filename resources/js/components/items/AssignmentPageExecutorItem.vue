<template>
	<tr @click="$root.assignLink(data.id)" class="cursor-point">
		<td class="ws-nowrap" v-if="data.id===data.mainId">
			{{ $root.makeFio(data.executor.surname, data.executor.firstname, data.executor.patronymic) }} <span class="greytxt">(Основной исполнитель)
			</span>
		</td>	
		<td class="ws-nowrap" v-else>
			{{ $root.makeFio(data.executor.surname, data.executor.firstname, data.executor.patronymic) }}
		</td>		
		<td class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.created_at"/>
		</td>		
		<td class="ta-center timestamp_font" v-if="data.status.id!=6">
			<vue-elem-timestamp :date-time="data.status.created_at"/>
		</td>
		<td class="ta-center" v-else>
			...
		</td>
		<td v-if="data.status.id==7" class="status_executing font-bold ta-right">
			{{ data.status.title }}
		</td>
		<td v-else-if="data.status.id==8" class="status_refused font-bold ta-right">
			{{ data.status.title }}
		</td>
		<td v-else-if="data.status.id==6" class="status_considering font-bold ta-right">
			{{ data.status.title }}
		</td>
		<td v-else-if="data.status.id==9" class="status_approved font-bold ta-right">
			{{ data.status.title }}
		</td>
		<td v-else-if="data.status.id==10" class="status_refused font-bold ta-right">
			{{ data.status.title }}
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