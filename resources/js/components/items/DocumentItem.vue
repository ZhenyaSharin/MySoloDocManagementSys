<template>
	<tr class="cursor-point row-hover" @click="$root.docLink(data.id)">
		<vue-td-index :index="index"/>
		<td>
			<vue-td-formattedtitle :text="data.description" :length="35"/>
		</td>
		<td class="ta-center timestamp_font ws-nowrap">
			<span v-if="data.orderNum!=null">
				<vue-td-formattedtitle :text="data.orderNum" :length="32"/>
			</span>
			<span v-else>
				...
			</span>
		</td>
		<td class="ta-center timestamp_font">
			{{ data.typeName }}
		</td>
		<td v-if="dateMode==1" class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<td v-else-if="dateMode==0" class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.creationDate" :short="true"/>&nbsp;
		</td>
		<template v-if="isDiruser==true">
			<td v-if="data.diruser!=false" scope="col" class="timestamp_font ta-center ws-nowrap" :title="$root.makeFio(data.diruser.user.surname, data.diruser.user.firstname, data.diruser.user.patronymic)">
				{{ $root.makeFio(data.diruser.user.surname, data.diruser.user.firstname, data.diruser.user.patronymic, diruserLen) }}
			</td>
			<td v-else scope="col" class="ta-center"> 
				...
			</td>
		</template>
		<td class="timestamp_font ta-center" v-if="data.baseId!=null">
			<a :href="'/document-'+data.baseId">
				Документ
			</a>
		</td>
		<td class="timestamp_font ta-center" v-else-if="data.baseAssignmentId!=null">
			<a :href="'/assignment-'+data.baseAssignmentId">
				Поручение
			</a>
		</td>
		<td class="ta-center" v-else>
			...
		</td>
		<vue-td-docstatus :date="data.created_at" :status="data.status"/>
	</tr>
</template>
<script>
	export default {
		props: {
			data: Object,
			dateMode: {
				type: Number,
				default: 0,
			},
			index: {
				type: Number,
				default: null,
			},
			isDiruser: {
				type: Boolean,
				default: false,
			}
		},
		data() {
			return {
				diruserLen: 24,
			}
		},
	}

</script>