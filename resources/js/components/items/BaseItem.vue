<template>
	<div v-if="baseDoc!=null" class="versions">
		<div class="col-md-12 d-flex justify-content-between align-items-center versions__underline  pb-1">
			<div>
				<i class="fas fa-level-down-alt fa-lg"/>
			</div>
			<div v-if="baseDoc.status.docstatusId==2">
				<a :href="'/document-'+baseDoc.id" class="font-bold">{{ baseDoc.description }}</a>&nbsp;<span class="greytxt">(Замечание)</span>
			</div>
			<div v-else>
				<a :href="'/document-'+baseDoc.id" class="font-bold">{{ baseDoc.description }}</a>&nbsp;<span class="greytxt">(Документ)</span>
			</div>
			<div class="d-flex justify-content-between baseitem_right">
				<div>
					От&nbsp;<vue-elem-timestamp :date-time="baseDoc.created_at"/>
				</div>
				<div>
					<span class="font-bold">
						{{ baseDoc.status.docStatusTitle }}	
					</span>
					&nbsp;
					<span class="font-bold">
						{{ $root.makeFio(baseDoc.author.surname, baseDoc.author.firstname, baseDoc.author.patronymic) }}
					</span>
				</div>
			</div>
		</div>
		<vue-item-base :baseDoc="baseDoc.base" :baseAssign="baseDoc.baseAssignment"/>
	</div>
	<div v-else-if="baseAssign!=null" class="versions">
		<div class="col-md-12 d-flex justify-content-between align-items-center versions__underline  pb-1">
			<div>
				<i class="fas fa-level-down-alt fa-lg"/>
			</div>
			<div>
				<a :href="'/assignment-'+baseAssign.id" class="font-bold">{{ baseAssign.text }}</a>&nbsp;<span class="greytxt">(Поручение)</span>
			</div>
			<div class="d-flex justify-content-between baseitem_right">
				<div>
					От&nbsp;<vue-elem-timestamp :date-time="baseAssign.created_at"/>
				</div>
				<div>
					<span v-if="baseAssign.status.id==6" class="status_considering">
						&nbsp;&nbsp;{{ baseAssign.status.title }}&nbsp;&nbsp;
					</span>
					<span v-if="baseAssign.status.id==7" class="status_executing">
						&nbsp;&nbsp;{{ baseAssign.status.title }}&nbsp;&nbsp;
					</span>
					<span v-if="baseAssign.status.id==8" class="status_refused">
						&nbsp;&nbsp;{{ baseAssign.status.title }}&nbsp;&nbsp;
					</span>
					<span v-if="baseAssign.status.id==9" class="status_approved">
						&nbsp;&nbsp;{{ baseAssign.status.title }}&nbsp;&nbsp;
					</span>
					<span v-if="baseAssign.status.id==10" class="status_refused">
						&nbsp;&nbsp;{{ baseAssign.status.title }}&nbsp;&nbsp;
					</span>
					&nbsp;
					<span class="font-bold ws-nowrap">
						{{ $root.makeFio(baseAssign.author.surname, baseAssign.author.firstname, baseAssign.author.patronymic) }}
					</span>
				</div>
			</div>
		</div>
		<vue-item-base :baseDoc="baseAssign.base" :baseAssign="baseAssign.baseAssignment"/>
	</div>
</template>
<script>
	export default {
		props: {
			baseDoc: Object,
			baseAssign: Object,
		},
	}
</script>
