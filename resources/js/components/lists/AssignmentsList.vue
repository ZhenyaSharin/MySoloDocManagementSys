<template>
	<div class="container">
		<h2>
			Поручения
		</h2>
		<br/>
		<div class="row d-flex align-items-center" v-if="usersList!=[]">
			<vue-template-newassignment :user-id="userId" :users-list="usersList"/>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header_custom font-bold font-up d-flex justify-content-between align-items-center" v-if="$root.selectedTitle==1">
						<div>
							Ваши поручения
						</div>
						<div class="selected_title cursor-point" @click="toggleTitle(2)">
							Поручения вам
						</div>
						<div class="selected_title cursor-point" @click="toggleTitle(3)">
							Все поручения
						</div>
					</div>
					<div class="card-header card-header_custom font-bold font-up d-flex justify-content-between align-items-center" v-else-if="$root.selectedTitle==2">
						<div class="selected_title cursor-point" @click="toggleTitle(1)">
							Ваши поручения
						</div>
						<div>
							Поручения вам
						</div>
						<div class="selected_title cursor-point ta-right" @click="toggleTitle(3)">
							Все поручения
						</div>
					</div>
					<div class="card-header card-header_custom font-bold font-up d-flex justify-content-between align-items-center" v-else-if="$root.selectedTitle==3">
						<div class="selected_title cursor-point" @click="toggleTitle(1)">
							Ваши поручения
						</div>
						<div class="selected_title cursor-point" @click="toggleTitle(2)">
							Поручения вам
						</div>
						<div class="ta-right">
							Все поручения
						</div>
					</div>
					<div class="card-body table_scroll_998_y">
						<!-- <template v-if="$root.spinOffSingle==true">
							<vue-template-listpagination class="vis_hid"/>
						</template>
						<template v-else>
							<template v-if="$root.selectedTitle==1">
								<vue-template-listpagination v-if="$root.assignsShortList.length>0" :count="$root.pagiCount" :current="$root.currPagiPage" :type="'assign'"/>
							</template>
							<template v-else-if="$root.selectedTitle==2">
								<vue-template-listpagination v-if="$root.assignsListExecutor.length>0" :count="$root.pagiCountEx" :current="$root.currPagiPageEx" :type="'assign'"/>
							</template>
							<template v-else-if="$root.selectedTitle==3">
								<vue-template-listpagination v-if="$root.assignsShortListAll.length>0" :count="$root.pagiCountAll" :current="$root.currPagiPageAll" :type="'assign'"/>
							</template>
						</template> -->
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th scope="col">
										#
									</th>
									<th scope="col">
										Поручение
									</th>
									<th class="ta-center" scope="col">
										Тип
									</th>
									<th class="ta-center" scope="col">
										Дата создания
									</th>
									<th class="ta-center" scope="col">
										Основание
									</th>
									<th v-if="$root.selectedTitle==2||$root.selectedTitle==3" class="ta-center" scope="col">
										Автор
									</th>
									<th v-else-if="$root.selectedTitle==1" class="ta-center" scope="col">
										Исполнитель(и)
									</th>
									<th class="ta-center" scope="col">
										Срок исполнения
									</th>
									<th class="ta-right" scope="col">
										Статус
									</th>
									<th v-if="$root.selectedTitle==1" class="ta-right" scope="col">
										Действие
									</th>
								</tr>
							</thead>
							<template v-if="$root.spinOffSingle===true">
								<tbody>
									<tr>
										<td colspan="100%" class="ta-center mt-4">
											<vue-spinner/>
										</td>
									</tr>
								</tbody>
							</template>
							<template v-else-if="$root.spinOffSingle===false">
								<template v-if="$root.selectedTitle==1">
									<!-- <tbody v-if="$root.assignsList.length>0"> -->
									<tbody>
										<template v-for="(item, index) in $root.assignsShortList">
											<template v-if="(index >= $root.assignsListFirst - 1) && (index <= $root.assignsListLast - 1)">
												<template v-if="item.main">
													<vue-item-assignment-author-multi :data="item" :get-arr="getArr" :index="index"/>
												</template>
												<template v-else>
													<vue-item-assignment-author :get-assign-id="getAssignId" :data="item" :index="index"/>
												</template>
											</template>
										</template>
									</tbody>
								</template>
								<template v-else-if="$root.selectedTitle==2">
									<!-- <tbody v-else-if="$root.assignsListExecutor.length>0"> -->
									<tbody>
										<template v-for="(item, index) in $root.assignsListExecutor">
											<vue-item-assignment-executor v-if="(index >= $root.assignsListFirstEx - 1) && (index <= $root.assignsListLastEx - 1)" :data="item" :index="index"/>
										</template>
									</tbody>
								</template>
								<template v-else-if="$root.selectedTitle==3">
									<!-- <tbody v-else-if="$root.assignsShortListAll.length>0"> -->
									<tbody>
										<template v-for="(item, index) in $root.assignsShortListAll">
											<template v-if="(index >= $root.assignsListFirstAll - 1) && (index <= $root.assignsListLastAll - 1)">
												<template v-if="item.main">
													<vue-item-assignment-executor-multi :data="item" :get-arr="getArr" :index="index"/>
												</template>
												<template v-else>
													<vue-item-assignment-executor :data="item" :index="index"/>
												</template>
											</template>
										</template>
									</tbody>
								</template>
								<template v-else>
									<tbody>
										<tr class="tr-greyplug">
											<th colspan="100%" class="ta-center font-up">
												Список пуст
											</th>
										</tr>
									</tbody>
								</template>
							</template>
						</table>
						<vue-template-choosepagination :type="'assign'"/>
					</div>
				</div>
			</div>	
		</div>
		<vue-modal-deleteassignauthor :id="delAssignId"/>
		<vue-modal-assignmentsmultilist :list="listModal" :type="listModalType"/>
	</div>
</template>
<script>
	export default {
		props: {
			userId: String,
		},
		data() {
			return {
				assignsList: [],
				assignsListAll: [],
				assignsListExecutor: [],
				spinOffAuthorAssigns: true,
				selectedTitle: 1,
				executorData: {
					initiatorId: this.userId,
					assignmentId: null,
					deadline: null,
					approvedUserId: null,
					initiatedAt: null,
				},
				usersList: [],
				respCount: null,
				authorAssignCount: null,
				delAssignId: null,
				assignsShortList: [],
				listModal: [],
				listModalType: '',
				currPagiPage: 1,
				currPagiPageAll: 1,
			}
		},
		created(){
			axios.post('api/getuserslist', {id: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						this.usersList = response.data.result;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
			if (this.$route.query.type) {
				this.$root.selectedTitle = this.$route.query.type;
			} else {
				this.$router.push({name: this.$route.name, query: { type: this.$root.selectedTitle }}).catch(err => {});
			}
			this.$root.pagiType = 'assign';
		},
		mounted() {
			this.toggleTitle(this.$route.query.type);
			// console.log(this.$root.assignsShortList);
		},
		methods: {
			toggleTitle: function(n = 1) {
				// this.$router.push({name: this.$route.name, query: { type: n }}).catch(err => {});
				if (n == 1) {
					console.log('ewff1');
					this.$root.selectedTitle = n;
					this.$root.spinOffSingle = true;
					this.assignsById();
					this.$root.pagiCount = this.$root.countPagesAssign(this.$root.assignsShortList);
					this.makeParamsPage(this.$root.currPagiPage);
					console.log(this.$root.pagiCount);
					this.$root.routerPush({
						type: n,
						page: this.$root.currPagiPage,
					});
				} else if (n == 2) {
					console.log('ewff2');
					this.$root.selectedTitle = n;
					this.$root.spinOffSingle = true;
					this.assignsByExecutor();
					this.makeParamsPage(this.$root.currPagiPageEx);
					this.$root.routerPush({
						type: n,
						page: this.$root.currPagiPageEx,
					});
				} else if (n == 3) {
					console.log('ewff3');
					this.$root.selectedTitle = n;
					this.$root.spinOffSingle = true;
					this.$root.assignsAll();
					this.$root.pagiCountAll = this.$root.countPagesAssign(this.$root.assignsShortListAll);
					this.makeParamsPage(this.$root.currPagiPageAll);
					this.$root.routerPush({
						type: n,
						page: this.$root.currPagiPageAll,
					});
				}
			},
			assignsById: function() {
				axios.post('api/assignmentsbyauthor', {authorId: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.$root.assignsList = response.data.result;
							this.$root.spinOffSingle = false;
							// let shortList = this.getMultiple(this.assignsList);
							this.$root.assignsShortList = this.$root.getMultiple(this.$root.assignsList);
							console.log(this.$root.assignsShortList);
							this.$root.pagiCount = this.$root.countPagesAssign(this.$root.assignsShortList);
                    		this.$root.getAssignsRange(this.$root.assignsShortList, this.$root.currPagiPage, this.$root.pagiCount, null);
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
			assignsByExecutor: function() {
				axios.post('api/assignexecutors', {executorId: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.$root.assignsListExecutor = response.data.result;
							this.$root.spinOffSingle = false;
							console.log(this.assignsListExecutor);
							this.$root.pagiCountEx = this.$root.countPagesAssign(this.$root.assignsListExecutor);
							console.log(this.$root.pagiCountEx);
                    		this.$root.getAssignsRange(this.$root.assignsListExecutor, this.$root.currPagiPageEx, this.$root.pagiCountEx, 'ex');
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
            refreshExecutor: function() {
                this.toggleTitle(2);
            },
    		assignLink: function(id, assignExecutorId = null) {
				if (assignExecutorId != null) {
					this.$root.makeViewedAssignment(assignExecutorId);
				};
				window.location.href = '/assignment-'+id;
			},
			getAssignId: function(data) {
				this.delAssignId = data.assignId;
			},
			getArr: function(data) {
				this.listModal = data.arr;
				this.listModalType = data.type;
				console.log(data);
			},
			makeParamsPage: function(n = 1) {
	            if (this.$route.query.type || this.$route.query.page) {
	                if (this.$route.query.type) {
	                    this.$root.selectedTitle = this.$route.query.type;
	                };
	                if (this.$route.query.page) {
	                    if (this.$root.selectedTitle == 1) {
	                        this.$root.currPagiPage = this.$route.query.page;
	                    } else if (this.$root.selectedTitle == 2) {
	                        this.$root.currPagiPageEx = this.$route.query.page;
	                    } else if (this.$root.selectedTitle == 3) {
	                        this.$root.currPagiPageAll = this.$route.query.page;
	                    };
	                };
	            } else {
	                this.$root.routerPush({
	                    type: this.$root.selectedTitle,
	                    page: n,
	                });
	            };
	        },
			countPages: function(arr) {
	            if (this.$root.selectedTitle == 1) {
	                this.makeParamsPage(this.$root.currPagiPage);
	            } else if (this.$root.selectedTitle == 2) {
	                this.makeParamsPage(this.$root.currPagiPageEx);   
	            } else if (this.$root.selectedTitle == 3) {
	                this.makeParamsPage(this.$root.currPagiPageAll);
	            };
	            return Math.floor(arr.length / 10) + 1;
	        },
			// changePagiPage: function(n) {
	        //     if (this.$root.selectedTitle == 1) {
	        //         this.$root.currPagiPage = n;
	        //         this.$root.getAssignsRange(this.assignsShortList, this.$root.currPagiPage, this.$root.pagiCount, false);
	        //     } else if (this.$root.selectedTitle == 3) {
	        //     	this.$root.currPagiPage = n;
	        //     	this.$root.getAssignsRange(this.assignsListExecutor, this.$root.currPagiPageEx, this.$root.pagiCountEx, true);
	        //     } else if (this.$root.selectedTitle == 3) {
	        //         this.$root.currPagiPageAll = n;
	        //         this.$root.getAssignsRange(this.assignsShortListAll, this.$root.currPagiPageAll, this.$root.pagiCountAll, true);
	        //     };
	        //     this.routerPush({
	        //         type: this.$root.selectedTitle,
	        //         page: n,
	        //     });
	        // },
		}
	}
</script>