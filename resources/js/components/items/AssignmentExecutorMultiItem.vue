<template>
	<tr class="cursor-point">
		<vue-td-index :index="index"/>
		<td>
			<vue-td-formattedtitle :text="data.arr[0].text" :length="50"/>
		</td>
		<td class="ta-center">
			{{ data.arr[0].type.title }}
		</td>
		<td class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.arr[0].created_at"/>&nbsp;<span v-if="data.arr[0].created_atupdated_at" class="redacted">(Изм.)</span>
		</td>
		<td class="ta-center" v-if="data.arr[0].baseId!=null">
			<a :href="'/assignment-'+ data.arr[0].baseId" target="_blank">
				Поручение
			</a>
		</td>
		<td class="ta-center" v-else-if="data.arr[0].documentId!=null">
			<a :href="'/document-'+ data.arr[0].documentId" target="_blank">
				Документ
			</a>
		</td>
		<td class="ta-center" v-else>
			...
		</td>
		<td class="ta-center ws-nowrap">
			{{ $root.makeFio(data.arr[0].author.surname, data.arr[0].author.firstname, data.arr[0].author.patronymic) }}
		</td>
		<td class="ta-center p-1 py-2" colspan="2">
			<button class="btn btn-primary no-round font-bold" style="width: 100%;" data-toggle="modal" data-target="#assignmentsMultiListModal" @click="getId()">
				Открыть
			</button>
		</td>
	</tr>
</template>
<script>
	export default {
		props: {
			data: Object,
			getArr: Function,
			index: {
				type: Number,
				default: null,
			},
		},
		data() {
			return {
				now: new Date(),
			}
		},
		created() {
			this.index = (this.index != null) ? this.$root.indexUpd(this.index) : null;
		},
		methods: {
			getStatus: function(deadline) {
				return this.$root.checkDate(deadline, this.now);
			},
			getId: function() {
				this.getArr({
					arr: this.data.arr,
					type: 'executor',
				})
			},
		},
	}
</script>