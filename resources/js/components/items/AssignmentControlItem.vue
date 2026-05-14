<template>
	<tr v-if="data.control.viewed_at!=null" class="cursor-point" @click="$root.assignLink(data.id)">
		<td>
			<vue-td-formattedtitle :text="data.text" :length="50"/>
			<br/>
			<span v-if="data.order==1" class="greytxt">
				Основной согл-нт
			</span>
		</td>
		<td class="ta-center">
			{{ data.type.title }}
		</td>
		<td class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.control.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<td class="ta-center ws-nowrap">
			{{ $root.makeFio(data.control.initiator.surname, data.control.initiator.firstname, data.control.initiator.patronymic) }}
		</td>
		<vue-td-dateleft :status="data.status.id" :date="data.deadline[0].deadline"/>
		<vue-td-assignstatus :status="data.status" :date-status="dateStatus"/>
	</tr>
	<tr v-else class="cursor-point notviewed" @click="$root.assignLink(data.id, data.id)">
		<td class="font-bold">
			<vue-td-formattedtitle :text="data.text" :length="50"/>
		</td>
		<td class="ta-center">
			{{ data.type.title }}
		</td>
		<td class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.control.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<td class="ta-center ws-nowrap">
			{{ $root.makeFio(data.control.initiator.surname, data.control.initiator.firstname, data.control.initiator.patronymic) }}
		</td>
		<vue-td-dateleft :status="data.status.id" :date="data.deadline[0].deadline"/>
		<vue-td-assignstatus :status="data.status" :date-status="dateStatus"/>
	</tr>
</template>
<script>
	export default {
		props: {
			data: Object,
		},
		data() {
			return {
				dateStatus: 0,
				now: new Date(),
			}
		},
		created() {
			this.dateStatus = this.$root.checkDate(this.data.deadline[0].deadline, this.now);
		},
	}

</script>