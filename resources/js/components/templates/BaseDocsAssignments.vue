<template>
	<div class="row">
		<div class="col-md-12 p-2 basetab d-flex justify-content-center align-items-center" @click="openBase()">
        	<div class="ta-center font-bold" v-if="type==1">
        		Основание для документа:
        	</div>
        	<div class="ta-center font-bold" v-else-if="type==2">
        		Основание для поручения:
        	</div>
<!--             <div class="ta-center font-bold" v-else-if="type==2">
                Основание для поручения:
            </div> -->
        	<div v-bind:class="[{ basetab__angle_rotated: basetabPanel }, angleBase]">
        		<i class="fas fa-caret-down fa-2x"/>
        	</div>
        </div>
        <div style="width: 100%" v-if="basetabPanel">
        	<div class="row">
        		<div class="col-md-4 ta-center font-bold cursor-point font-up px-0 pt-1" @click="selectBase(1)">
            		<div class="col-md-12 ta-center font-bold font-up px-0 py-1">
                		<span class="agr-title" v-bind:class="{ basetab__title_check: basetabPanelType===1 }">
                			Документ
                		</span>
                	</div>
                </div>
            	<div class="col-md-4 ta-center font-bold cursor-point font-up px-0 pt-1" @click="selectBase(2)">
            		<div class="col-md-12 ta-center font-bold font-up px-0 py-1">
                		<span class="agr-title" v-bind:class="{ basetab__title_check: basetabPanelType===2 }">
                			Поручение
                		</span>
                	</div>
            	</div>
            	<div class="col-md-4 ta-center px-0 pt-1" @click="selectBase(3)">
            		<div class="col-md-12 ta-center px-0 py-1">
                		<span class="agr-title font-bold cursor-point font-up" v-bind:class="{ basetab__title_check: basetabPanelType===3 }">
                			Замечание
                		</span>
                		<br>
                		<small class="greytxt">
                			* отклонённые документы
                		</small>
                	</div>
            	</div>
            </div>
        	<div v-if="basetabPanelType===1" class="basetab__search py-1">
        		<div class="form-group row mb-2">
        			<div class="col-md-12">
        				<vue-multiselect v-model="$root.baseDocId" :options="docs" placeholder="Поиск по документам" label="description" track-by="id" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" :custom-label="$root.baseDocTitle" selected-label="Выбрано" @change="clearBase(1)">
        					<template slot="noOptions">
        						Список пуст.
        					</template>
							<template slot="noResult">
								Ничего не найдено...
							</template>
        				</vue-multiselect>
        			</div>
        		</div>
        	</div>
        	<div v-else-if="basetabPanelType===2" class="basetab__search py-1">
        		<div class="col-md-12">
	        		<div class="form-group row mb-2">
	        			<vue-multiselect v-model="$root.baseAssignId" :options="assigns" placeholder="Поиск по поручениям" label="description" track-by="id" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" :custom-label="$root.baseAssignTitle" selected-label="Выбрано" @change="clearBase(2)">
	        				<template slot="noOptions">
	        					Список пуст.
	        				</template>
							<template slot="noResult">
								Ничего не найдено...
							</template>
	        			</vue-multiselect>
	        		</div>
	        	</div>
        	</div>
        	<div v-if="basetabPanelType===3" class="basetab__search py-1">
        		<div class="form-group row mb-2">
        			<div class="col-md-12">
        				<vue-multiselect v-model="$root.baseRefusedDocId" :options="docsWithout" placeholder="Поиск по замечаниям" track-by="id" select-label="Enter чтобы выбрать" :preselect-first="false"  deselect-label="Enter чтобы удалить" :custom-label="$root.baseDocTitle" :show-labels="false" selected-label="Выбрано" @change="clearBase(3)">
        					<template slot="noOptions" slot-scope="props">
        						Список пуст.
        					</template>
							<template slot="noResult" slot-scope="props">
								Ничего не найдено...
							</template>
        				</vue-multiselect>
        			</div>
        		</div>
        	</div>
        	<template v-if="$root.baseDocId!=null">
        		<a :href="'/document-'+ $root.baseDocId.id" target="_blank" class="font-bold m-2" title="Открыть в новом окне">
        			<span class="blacktxt"><i class="fas fa-check-circle"/>&nbsp;&nbsp;Выбран документ:</span>&nbsp;<vue-td-formattedtitle :text="$root.baseDocId.description" :length="40"/><i class="fas fa-external-link-alt fa-lg"/>
        		</a>
        	</template>
        	<template v-else-if="$root.baseAssignId!=null">
        		<a :href="'/assignment-'+ $root.baseAssignId.id" target="_blank" class="font-bold m-2" title="Открыть в новом окне">
        			<span class="blacktxt"><i class="fas fa-check-circle"/>&nbsp;&nbsp;Выбрано поручение:</span>&nbsp;<vue-td-formattedtitle :text="$root.baseAssignId.text" :length="40"/><i class="fas fa-external-link-alt fa-lg"/>
        		</a>
        	</template>
            <template v-else-if="$root.baseRefusedDocId!=null">
                <a :href="'/document-'+ $root.baseRefusedDocId.id" target="_blank" class="font-bold m-2" title="Открыть в новом окне">
                    <span class="blacktxt"><i class="fas fa-check-circle"/>&nbsp;&nbsp;Выбрано замечание:</span>&nbsp;<vue-td-formattedtitle :text="$root.baseRefusedDocId.description" :length="40"/><i class="fas fa-external-link-alt fa-lg"/>
                </a>
            </template>
<!--         	<div v-for="item in $parent.agreeValue" class="font-bold m-2">
				<i class="fas fa-check-circle"/>&nbsp;&nbsp;{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
			</div> -->
        </div>
	</div>
</template>
<script>
	export default {
		props: {
			// getBase: Function,
			type: Number,
            opened: {
                default: false,
                type: Boolean,
            },
            baseTab: {
                default: null,
                type: Number,
            },
		},
		data() {
			return {
				basetabPanel: false,
				angleBase: 'basetab__angle',
				baseDoc: '',
				baseNote: null,
				basetabPanelType: 1,
				docsWithout: [],
                docs: [],
                assigns: [],
				// assignBaseDoc: null,
                docsListAll: [],
                assignsListAll: [],
                assigns: [],
                docs: [],
			}
		},
        created() {
            this.assignsAll();
            this.docsAll();
            if (this.opened === true) {
                this.basetabPanel = true;
                this.basetabPanelType = this.baseTab;
            }
        },
		methods: {
			openBase: function() {
				this.basetabPanel = (this.basetabPanel === false) ? true : false;
				// this.allDocs();
                // if (this.basetabPanel === false) {
                //     this.clearBase();
                // }
			},
			selectBase: function(n) {
				this.basetabPanelType = n;
				this.$root.baseAssignId = null;
				this.$root.baseDocId = null;
                this.$root.baseRefusedDocId = null;
			},
			allDocs: function() {
				this.docsWithout = [];
				this.$root.docsAllRet().forEach(item => {
					if (item.status.docstatusId === 2) {
						this.docsWithout.push(item);
					}
				});
			},
            // getOptions: function() {
            //     if (this.type == 1) {
            //         this.docs = this.filterExceptId(this.$root.docsListAll, this.$parent.id);
            //         this.assigns = this.$root.assignsListAll;
            //     } else if (this.type == 2) {
            //         this.docs = this.$root.docsListAll;
            //         this.assigns = this.filterExceptId(this.$root.assignsListAll, this.$parent.id);
            //     } else {
            //         this.docs = this.$root.docsListAll;
            //         this.assigns = this.$root.assignsListAll;
            //     }
            // },
            // filterExceptId: function(arr, id) {
            //     // console.log('arr');
            //     // console.log(arr);
            //     let filter = [];
            //     arr.forEach(item => {
            //         if (item.id != id) {
            //             filter.push(item);
            //         }
            //     });
            //     return filter;
            // },
            getOptions: function(mode = 'docs', without = false) {
                let docs = [];
                let assigns = [];
                let docsAll = this.$root.docsAllRet();
                console.log(mode);
                if (mode === 'docs') {
                    if (this.type == 1) {
                        docs = this.filterExceptId(docsAll, this.$parent.id);
                    } else if (this.type == 2) {
                        docs = docsAll;
                    }
                    return docs;
                } else if (mode === 'assigns') {
                    if (this.type == 1) {
                        assigns = this.$root.assignsListAll;
                    } else if (this.type == 2) {
                        assigns = this.filterExceptId(this.$root.assignsListAll, this.$parent.id);
                    }
                    return assigns;
                }
            },
            // filterExceptId: function(arr, id) {
            //     console.log(arr);
            //     let filter = [];
            //     arr.forEach(item => {
            //         if (item.id != id) {
            //             filter.push(item);
            //         }
            //     });
            //     return filter;
            // },
            assignsAll: function() {
                axios.post('api/getallassignments', {
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                    .then(response => {
                        if (response.data.error == 0) {
                            this.assignsListAll = response.data.result;
                            if (this.type == 1) {
                                this.assigns = this.assignsListAll;
                            } else if (this.type == 2) {
                                // this.assigns = this.filterExceptId(this.$root.assignsListAll, this.$parent.id);
                                this.assignsListAll.forEach(item => {
                                    if (item.id != this.$parent.id) {
                                        this.assigns.push(item);
                                    }
                                });
                            }
                        } else {
                            alert(response.data.error_message);
                        }
                    }).catch(error => {
                        alert('Ошибка получения данных');
                        console.log(error);
                    });
            },
            docsAll: function(mode = false) {
                let data = {
                    creationDate: null,
                    ascDesc: this.$root.orderMode,
                };
                data.creationDate = (mode === true) ? 1 : null;
                axios.post('api/getdocslist', data, {
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                    .then(response => {
                        if (response.data.error == 0) {
                            this.docsListAll = response.data.result;

                            if (this.type == 1) {
                                this.docs = this.docsListAll;
                            } else if (this.type == 2) {
                                this.docsListAll.forEach(item => {
                                    if (item.id != id) {
                                        this.docs.push(item);
                                    }
                                });
                            }
                            this.docsListAll.forEach(item => {
                                if (item.status.docstatusId === 2) {
                                    this.docsWithout.push(item);
                                }
                            });
                        } else {
                            alert(response.data.error_message);
                        }
                    }).catch(error => {
                        alert('Ошибка получения данных');
                        console.log(error);
                    });
            },
		}
	}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>