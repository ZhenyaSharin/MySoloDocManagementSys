<template>
	<tr v-if="data.viewed_at!=null" class="row-hover">
		<vue-td-index :index="index"/>
		<td class="font-bold">
			<a :href="'/assignment-'+ data.id" target="_blank">
				<vue-td-formattedtitle :text="data.text" :length="50"/>
			</a>
			<span v-if="data.deadline[0].approved_at==null&&data.deadline[0].refused_at==null" class="status_refused" title="Запрошен перенос срока исполнения">
				&nbsp;&nbsp;<i class="fas fa-exclamation fa-lg"/>
			</span>
		</td>
		<td class="ta-center">
			{{ data.type.title }}
		</td>
		<td class="ta-center px-1">
			<vue-elem-timestamp :date-time="data.created_at" :short="false"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
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
		<td v-if="data.executorId!=null" class="ta-center">
			<vue-link-userfio :data="data.executor" :short="true"/>
		</td>
		<td v-else class="ta-center">
			...
		</td>
		<vue-td-dateleft :status="data.status.id" :date="getDeadline()"/>
		<vue-td-assignstatus :status="data.status" :date-status="dateStatus"/>
		<td v-if="button==true" scope="col" class="ta-center">
			<button v-if="data.status.id==6||data.status.id==7" class="btn btn-danger no-round btn-squarebtn" title="Удалить заявку" data-toggle="modal" data-target="#deleteAssignAuthorModal" @click="getId(data.id)">
				<i class="fas fa-times fa-lg"/>
			</button>
			<button v-else class="btn btn-secondary no-round btn-squarebtn" title="Удалить заявку" disabled>
				<i class="fas fa-times fa-lg"/>
			</button>
		</td>
	</tr>
	<tr v-else class="notviewed">
		<vue-td-index :index="index"/>
		<td class="font-bold">
			<a :href="'/assignment-'+ data.id" target="_blank">
				<vue-td-formattedtitle :text="data.text" :length="50"/>
			</a>
			<span v-if="data.deadline[0].approved_at==null&&data.deadline[0].refused_at==null" class="status_refused" title="Запрошен перенос срока исполнения">
				&nbsp;&nbsp;<i class="fas fa-exclamation fa-lg"/>
			</span>
		</td>
		<td class="ta-center">
			{{ data.type.title }}
		</td>
		<td class="ta-center px-1">
			<vue-elem-timestamp :date-time="data.created_at" :short="false"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
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
		<td v-if="data.executorId!=null" class="ta-center timestamp_font">
			<vue-link-userfio :data="data.executor" :short="true"/>
		</td>
		<td v-else class="ta-center">
			...
		</td>
		<vue-td-dateleft :date="getDeadline()" :status="data.status.id"/>
		<vue-td-assignstatus :status="data.status" :date-status="dateStatus"/>
		<td v-if="button==true" scope="col" class="ta-center">
			<button v-if="data.status.id==6||data.status.id==7" class="btn btn-danger no-round btn-squarebtn" title="Удалить заявку" data-toggle="modal" data-target="#deleteAssignAuthorModal" @click="getId(data.id)">
				<i class="fas fa-times fa-lg"/>
			</button>
			<button v-else class="btn btn-secondary no-round btn-squarebtn" title="Удалить заявку" disabled>
				<i class="fas fa-times fa-lg"/>
			</button>
		</td>
	</tr>
</template>
<script>
	export default {
		props: {
			data: Object,
			getAssignId: Function,
			button: {
				type: Boolean,
				default: true,
			},
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
		methods: {
			getId: function(id) {
				this.getAssignId({
					assignId: id,
				})
			},
			getDeadline: function() {
				for (let i = 0; i < this.data.deadline.length; i++) {
					if (this.data.deadline[i].approved_at != null) {
						return this.data.deadline[i].deadline;
					}
				};
			}
		}
	}

</script>