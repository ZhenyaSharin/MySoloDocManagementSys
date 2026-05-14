<template>
	<div class="modal fade" id="deleteRelationModal" tabindex="-1" role="dialog" aria-labelledby="deleteRelationModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="deleteRelationModalTitle">
						Удалить связь ???
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
<!-- 				<pre>
					{{ data }}
				</pre> -->
				<table class="table">
					<thead>
						<tr>
							<th v-if="data.documentId1!=null" scope="col" class="ta-center">
								Карточка №1
							</th>
						    <th v-else-if="data.assignmentId1!=null" scope="col" class="ta-center">
						    	Поручение №1
						    </th>
						    <th v-if="data.documentId2!=null" scope="col" class="ta-center">
						    	Карточка №2
						    </th>
						    <th v-else-if="data.assignmentId2!=null" scope="col" class="ta-center">
						    	Поручение №2
						    </th>
						</tr>
					</thead>
					<tbody>
						<tr class="table-warning">
							<td class="ta-center" v-if="data.documentId1!=null">
								<a :href="'/document-'+ data.documentId1" target="_blank" class="font-bold" title="Открыть в новом окне">
									<span v-if="data.documentDataNum1!=null">Док-т №{{ data.documentDataNum1 }}</span>&nbsp;<vue-td-formattedtitle :text="data.documentData1" :length="40"/>
								</a>
								<br/>
								<span class="greytxt">
									(документ)
								</span>
							</td>
							<td class="ta-center" v-else-if="data.assignmentId1!=null">
								<a :href="'/assignment-'+ data.assignmentId1" target="_blank" class="font-bold" title="Открыть в новом окне">
									<vue-td-formattedtitle :text="data.assignmentData1" :length="40"/>
								</a>
								<br/>
								<span class="greytxt">
									(поручение)
								</span>
							</td>
							<td class="ta-center" v-if="data.documentId2!=null">
								<a :href="'/document-'+ data.documentId2" target="_blank" class="font-bold" title="Открыть в новом окне">
									<span v-if="data.documentDataNum2!=null">Док-т №{{ data.documentDataNum2 }}</span>&nbsp;<vue-td-formattedtitle :text="data.documentData2" :length="40"/>
								</a>
								<br/>
								<span class="greytxt">
									(документ)
								</span>
							</td>
							<td class="ta-center" v-else-if="data.assignmentId2!=null">
								<a :href="'/assignment-'+ data.assignmentId2" target="_blank" class="font-bold" title="Открыть в новом окне">
									<vue-td-formattedtitle :text="data.assignmentData2" :length="40"/>
								</a>
								<br/>
								<span class="greytxt">
									(поручение)
								</span>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="modal-footer ta-center">
					<button class="btn btn-primary no-round flex-sm-fill" @click="removeRel(data.id)">
	                    Удалить
	                </button>
	                <button class="btn btn-danger no-round flex-sm-fill" data-dismiss="modal">
	                    Отмена
	                </button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			data: Object,
		},
		methods: {
			removeRel: function(id) {
				axios.post('api/updaterelation', {
					id: id,
					remove: 1,
				}, {
			        headers: {
			        	"Content-Type": "application/json"
			        }
			    })
					.then(response => {
						if (response.data.error == 0) {
							console.log('Просмотрено');
							this.$parent.removeErr = false;
							this.$router.go();
						} else {
							this.$parent.removeErr = true;
						}
					}).catch(error => {
						alert('Ошибка получения данных7');
						this.$parent.removeErr = true;
						console.log(error);
					});
			},
		}
	}
</script>