<template>
	<div class="modal-content mt-2 no-round">
		<div class="modal-header">
			<h5 class="modal-title font-up font-bold" id="AgreementsListLabel">
				Список связей
			</h5>
			<button v-if="$root.openRels==true" type="button" class="close  btn__hide-red" @click="$root.openRelsFunc(false)" title="Закрыть форму связей">
				<span aria-hidden="true">
					Скрыть
				</span>
			</button>
		</div>
<!-- 		<pre>
			{{ $root.relationsList }}
		</pre> -->
		<div class="modal-body table_scroll_998_y">
			<div class="alert alert-danger d-flex justify-content-between align-items-center" v-if="removeErr==true">
		    	<div>
		    		<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Не удалось удалить связь...
		    	</div>
		    	<div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
		        	<i class="fas fa-times fa-lg"/>
		        </div>
		    </div>
		    <template v-if="authorId==userId">
		    	<template v-if="$root.openRels==true">
			    	<template v-if="($root.filteredDocs.length > 0) && ($root.filteredAssigns.length > 0)">
			    		<vue-template-relationsadd/>
			    	</template>
			    	<template v-else>
			    		<div class="width100 ta-center greyblck my-4 pt-4">
							<vue-spinner/>
						</div>
			    	</template>
		    	</template>
		    </template>
			<div class="table_scroll_y" v-if="$root.relationsList.length!=0">
				<template v-for="(item, index) in $root.relationsList">
					<vue-item-relation v-if="item.mainId==null" :index="index" :data="item" :get-relation-data="getRelationData" :del-mode="true"/>	
				</template>
			</div>
			<template v-else>
				<div class="row d-flex">
					<div class="col-12">
						<table class="table">
							<tbody>
								<tr class="tr-greyplug">
									<th colspan="100%" class="ta-center font-up">
										Список пуст
									</th>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</template>
		</div>
		<template v-if="delRelationData!=null">
			<vue-modal-deleterelation :data="delRelationData"/>
		</template>
	</div>
</template>
<script>
	export default {
		props: {
			id: {
				type: String,
				default: null,
			},
			type: String,
			userId: String,
			authorId: {
				type: String,
				default: null,
			},
		},
		data() {
			return {
				relArr: [],
				docArr: [],
				assignArr: [],
				removeErr: false,
				delRelationData: null,
			}
		},
		created() {
			// this.getRelations();
		},
		methods: {
			getRelationData: function(data) {
				this.delRelationData = data;
			},
			closeMsg: function() {
				this.removeErr = false;
			},
		}
	}
</script>