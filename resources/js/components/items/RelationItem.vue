<template>
	<div class="row pl-2">
		<div v-bind:key="index" class="col-12 tr-relation d-flex justify-content-between align-items-center pb-1">
			<div class="d-flex justify-content-between align-items-center flex-fill">
				<div v-if="data.sub!=null" class="px-2 py-4">
					{{ index + 1 }}<span v-if="data['sub'].length>0">&nbsp;&nbsp;<i class="fas fa-level-down-alt fa-lg"/></span>
				</div>
				<div v-else class="px-2 py-4">
					{{ index + 1 }}
				</div>
				<template v-if="data.documentId1!=null">
					<div class="p-1 flex-fill">
						<a :href="'/document-'+ data.documentId1" target="_blank" class="font-bold" title="Открыть в новом окне">
							<span v-if="data.documentData1.orderNum!=null">
								Док-т №&nbsp;<vue-td-formattedtitle :text="data.documentData1.orderNum" :length="20"/>&nbsp;
							</span>
							<vue-td-formattedtitle :text="data.documentData1.description" :length="40"/>
						</a>
						<br/>
						<span class="greytxt">
							Документ&nbsp;<span class="font-bold">({{ data.documentData1.type.title }})</span>
						</span>
						<br/>
						<span class="greytxt">
							Дата док-та:&nbsp;
						</span>
						<span v-if="data.documentData1.creationDate!=null">
							<vue-elem-timestamp :date-time="data.documentData1.creationDate" :short="true"/>
						</span>
						<span v-else class="greytxt font-bold">
							&nbsp;Не указано
						</span>
						<span class="greytxt">
							,&nbsp;Номер док-та:&nbsp;
						</span>
						<span v-if="data.documentData1.orderNum!=null">
							<vue-td-formattedtitle :text="data.documentData1.orderNum" :length="16"/>
						</span>
						<span v-else class="greytxt font-bold">
							&nbsp;Не указано
						</span>
					</div>
				</template>
				<template v-else-if="data.assignmentId1!=null">
					<div class="p-1 flex-fill">
						<a :href="'/assignment-'+ data.assignmentId1" target="_blank" class="font-bold" title="Открыть в новом окне">
							<vue-td-formattedtitle :text="data.assignmentData1.text" :length="40"/>
						</a>
						<br/>
						<span class="greytxt">
							Поручение&nbsp;<span class="font-bold">({{ data.assignmentData1.type.title }})</span>
						</span>
						<br/>
						<span class="greytxt">
							Дата док-та:&nbsp;
						</span>
						<span v-if="data.assignmentData1.creationDate">
							<vue-elem-timestamp :date-time="data.assignmentData1.creationDate" :short="true"/>
						</span>
						<span v-else class="greytxt font-bold">
							&nbsp;Не указано
						</span>
					</div>
				</template>
			</div>
			<div class="d-flex justify-content-end align-items-center flex-fill">
				<template v-if="data.documentId2!=null">
					<div class="px-1 flex-fill">
						<a :href="'/document-'+ data.documentId2" target="_blank" class="font-bold" title="Открыть в новом окне">
							<span v-if="data.documentData2.orderNum!=null">
								Док-т №&nbsp;<vue-td-formattedtitle :text="data.documentData2.orderNum" :length="20"/>&nbsp;
							</span>
							<vue-td-formattedtitle :text="data.documentData2.description" :length="40"/>
						</a>
						<br/>
						<span class="greytxt">
							Документ&nbsp;<span class="font-bold">({{ data.documentData2.type.title }})</span>
						</span>
						<br/>
						<span class="greytxt">
							Дата док-та:&nbsp;
						</span>
						<span v-if="data.documentData2.creationDate!=null">
							<vue-elem-timestamp :date-time="data.documentData2.creationDate" :short="true"/>
						</span>
						<span v-else class="greytxt font-bold">
							&nbsp;Не указано
						</span>
						<span class="greytxt">
							,&nbsp;Номер док-та:&nbsp;
						</span>
						<span v-if="data.documentData2.orderNum!=null">
							<vue-td-formattedtitle :text="data.documentData2.orderNum" :length="16"/>
						</span>
						<span v-else class="greytxt font-bold">
							&nbsp;Не указано
						</span>
					</div>
				</template>
				<template v-else-if="data.assignmentId2!=null">
					<div class="px-1 flex-fill">
						<a :href="'/assignment-'+ data.assignmentId2" target="_blank" class="font-bold" title="Открыть в новом окне">
							<vue-td-formattedtitle :text="data.assignmentData2.text" :length="40"/>
						</a>
						<br/>
						<span class="greytxt">
							Поручение&nbsp;<span class="font-bold">({{ data.assignmentData2.type.title }})</span>
						</span>
						<br/>
						<span class="greytxt">
							Дата док-та:&nbsp;
						</span>
						<span v-if="data.assignmentData2.creationDate">
							<vue-elem-timestamp :date-time="data.assignmentData2.creationDate" :short="true"/>
						</span>
						<span v-else class="greytxt font-bold">
							&nbsp;Не указано
						</span>
					</div>
<!-- 					<div class="pl-4 ta-center">
						<vue-td-formattedtitle :text="data.assignmentData2.type.title" :length="20"/>
					</div> -->
				</template>
				<div class="ta-center px-4">
					<vue-elem-timestamp :date-time="data.created_at"/>
				</div>
				<template v-if="$parent.authorId==$parent.userId">
					<div class="ta-center px-1 pr-2" v-if="delMode==true">
						<button class="btn btn-danger no-round btn-squarebtn" title="Удалить связь" data-toggle="modal" data-target="#deleteRelationModal" @click="getId()">
							<i class="fas fa-times fa-lg"/>
						</button>
					</div>
					<div class="ta-center px-1 pr-2" v-else>
						<button class="btn btn-danger no-round btn-squarebtn vis_hid">
							<i class="fas fa-times fa-lg"/>
						</button>
					</div>
				</template>	
			</div>
		</div>
		<div class="col-12 pl-4" v-for="(item, index) in data.sub">
			<vue-item-relation :index="index" :data="item"/>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			index: String,
			data: Object,
			getRelationData: Function,
			delMode: false,
		},
		methods: {
			getId: function() {
				let data = {
					id: this.data.id,
					documentId1: this.data.documentId1,
					documentData1: null,
					documentDataNum1: null,
					assignmentId1: this.data.assignmentId1,
					assignmentData1: null,
					documentId2: this.data.documentId2,
					documentData2: null,
					documentDataNum2: null,
					assignmentId2: this.data.assignmentId2,
					assignmentData2: null,
				};
				if (this.data.documentId1 != null) {
					data.documentData1 = this.data.documentData1.description;
					data.documentDataNum1 = (this.data.documentData1.orderNum != null) ? this.data.documentData1.orderNum : null;
				} else if (this.data.assignmentId1 != null) {
					data.assignmentData1 = this.data.assignmentData1.text;
				};
				if (this.data.documentId2 != null) {
					data.documentData2 = this.data.documentData2.description;
					data.documentDataNum2 = (this.data.documentData2.orderNum != null) ? this.data.documentData2.orderNum : null;
				} else if (this.data.assignmentId2 != null) {
					data.assignmentData2 = this.data.assignmentData2.text;
				};
				this.getRelationData(data)
			},
		},
	}
</script>