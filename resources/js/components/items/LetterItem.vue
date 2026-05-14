<template>
	<tr class="cursor-point row-hover" @click="$root.docLink(data.id)">
		<vue-td-index :index="index"/>
		<td scope="col">
			<vue-td-formattedtitle :text="data.description" :length="50"/>
		</td>
		<td v-if="data.orderNum!=null" scope="col" class="ta-center">
			<!-- {{ data.orderNum }} -->
			<vue-td-formattedtitle :text="data.orderNum" :length="32"/>
		</td>
		<td v-else scope="col" class="ta-center">
			...
		</td>
		<td v-if="dateMode==1" class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<td v-else-if="dateMode==0" class="ta-center timestamp_font">
			<vue-elem-timestamp :date-time="data.creationDate" :short="true"/>
		</td>
		<td v-if="data.diruser!=false" scope="col" class="timestamp_font ta-center ws-nowrap" :title="$root.makeFio(data.diruser.user.surname, data.diruser.user.firstname, data.diruser.user.patronymic)">
			{{ $root.makeFio(data.diruser.user.surname, data.diruser.user.firstname, data.diruser.user.patronymic, diruserLen) }}
		</td>
		<td v-else scope="col" class="ta-center"> 
			...
		</td>
		<td scope="col" class="ta-right timestamp_font" v-if="data.baseId!=null">
			<a :href="'/document-'+ data.baseId">
				Документ
			</a>
		</td>
		<td class="timestamp_font ta-center" v-else-if="data.baseAssignmentId!=null">
			<a :href="'/assignment-'+data.baseAssignmentId">
				Поручение
			</a>
		</td>
		<td scope="col" class="ta-right" v-else>
			...
		</td>
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
		},
		data() {
			return {
				diruserLen: 24,
			}
		},
	}

</script>