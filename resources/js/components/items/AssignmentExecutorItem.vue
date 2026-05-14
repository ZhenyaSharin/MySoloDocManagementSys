<template>
	<tr v-if="data.viewed_at!=null" class="cursor-point" @click="$root.assignLink(data.id)">
		<vue-td-index :index="index"/>
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
			<vue-elem-timestamp :date-time="data.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<td class="ta-center" v-if="data.baseId!=null">
			<a :href="'/assignment-'+data.baseId" target="_blank">
				Поручение
			</a>
		</td>
		<td class="ta-center" v-else-if="data.documentId!=null">
			<a :href="'/document-'+data.documentId" target="_blank">
				Документ
			</a>
		</td>
		<td class="ta-center" v-else>
			...
		</td>
		<td class="ta-center ws-nowrap">
			{{ $root.makeFio(data.author.surname, data.author.firstname, data.author.patronymic) }}
		</td>
		<vue-td-dateleft :status="data.status.id" :date="data.deadline[0].deadline"/>
		<vue-td-assignstatus :status="data.status" :date-status="dateStatus"/>
	</tr>
	<tr v-else class="cursor-point notviewed" @click="$root.assignLink(data.id, data.id)">
		<vue-td-index :index="index"/>
		<td class="font-bold">
			<vue-td-formattedtitle :text="data.text" :length="50"/>
		</td>
		<td class="ta-center">
			{{ data.type.title }}
		</td>
		<td class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<td class="ta-center" v-if="data.baseId!=null">
			<a :href="'/assignment-'+data.baseId" target="_blank">
				<!-- {{ data.base.text }} -->
				Поручение
			</a>
		</td>
		<td class="ta-center" v-else-if="data.documentId!=null">
			<a :href="'/document-'+data.documentId" target="_blank">
				<!-- {{ data.documentBase.description }} -->
				Документ
			</a>
		</td>
		<td class="ta-center" v-else>
			...
		</td>
		<td class="ta-center ws-nowrap">
			{{ $root.makeFio(data.author.surname, data.author.firstname, data.author.patronymic) }}
		</td>
		<vue-td-dateleft :status="data.status.id" :date="this.data.deadline[0].deadline"/>
		<vue-td-assignstatus :status="data.status" :date-status="dateStatus"/>
	</tr>
</template>
<script>
	export default {
		props: {
			data: Object,
			index: {
				type: Number,
				default: null,
			},
		},
		data() {
			return {
				dateStatus: 0,
				now: new Date(),
			}
		},
		created() {
			this.dateStatus = this.$root.checkDate(this.data.deadline[0].deadline, this.now);
			this.index = (this.index != null) ? this.$root.indexUpd(this.index) : null;
		},
	}
</script>