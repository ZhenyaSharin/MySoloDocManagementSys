<template>
	<div class="container">
		<h2>
			Информационная лента
		</h2>
		<div v-if="$root.roleData.roleId==1">
			<br/>
			<div class="alert alert-success d-flex justify-content-between align-items-center" v-if="userMessage==1">
		        <div>
		        	<i class="far fa-thumbs-up fa-lg"/>&nbsp;&nbsp;Вы успешно добавили новую запись
		        </div>
		        <div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
		        	<i class="fas fa-times fa-lg"/>
		        </div>
		    </div>
		    <div class="alert alert-danger d-flex justify-content-between align-items-center" v-else-if="userMessage==2">
		    	<div>
		    		<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Не удалось создать новую запись, возникла ошибка...
		    	</div>
		    	<div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
		        	<i class="fas fa-times fa-lg"/>
		        </div>
		    </div>
			<div class="card">
				<div class="card-header font-up font-bold">
					Добавить новую запись
				</div>
				<div class="card-body">
					<div class="row">
			        	<div class="col-md-12">
			        		<div class="form-group row">
			        			<label for="blog-topic" class="col-md-4 col-form-label text-md-right">
			        				Тема:
			        			</label>
			        			<div class="col-md-6">
									<input type="text" id="blog-topic" class="form-control" maxlength="64" v-model="newItem.title" placeholder="макс. 64 символа" required>
			        			</div>
			        		</div>
			        	</div>
			        	<div class="col-md-12">
			        		<div class="form-group row">
			        			<label for="blog-text" class="col-md-4 col-form-label text-md-right">
			        				Текст поста:
			        			</label>
			        			<div class="col-md-6">
			        				<textarea name="" id="blog-text" rows="4"  class="form-control" placeholder="макс.кол-во знаков - 512" maxlength="512" v-model="newItem.text">
										
									</textarea>
			        			</div>
			        		</div>
			        	</div>
			        	<div class="col-md-12">
			        		<div class="form-group row">
			        			<div class="offset-md-4 col-md-6">
			        				<button class="btn btn-primary font-bold no-round font-up p-3 box-shad" @click="createBlogItem()">
			        					Создать запись
			        				</button>
			        			</div>
			        		</div>
			        	</div>
			        </div>
				</div>
			</div>
		</div>
		<br/>
		<div v-for="(item, index) in blogItems" class="card my-4">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						Тема:&nbsp;&nbsp;<span class="font-bold">{{ item.title }}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span v-if="userId==3" title="Редактировать запись" class="cursor-point redact" @click="getBlogItemInfo(index)"  data-toggle="modal" data-target="#editBlogItemModal"><i class="fas fa-pencil-alt fa-lg"/></span>&nbsp;&nbsp;&nbsp;<span  v-if="userId==3" title="Удалить запись" class="cursor-point redact" data-toggle="modal" data-target="#removeBlogItemModal" @click="getBlogItemInfo(index)"><i class="far fa-trash-alt fa-lg"/></span>
					</div>
					<div class="font-bold p-1" style="background-color: #D3D3D3;">
						<vue-elem-timestamp :date-time="item.created_at"/>
					</div>
				</div>
				<div v-if="item.text!=null" class="mt-2">
					{{ item.text }}
				</div>
				<div v-else class="mt-2">
					...
				</div>
			</div>
		</div>
		<div class="modal fade" id="editBlogItemModal" tabindex="-1" role="dialog" aria-labelledby="editBlogItemTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content no-round">
					<div class="modal-header">
						<h5 class="modal-title font-up font-bold" id="editBlogItemTitle">
							Редактирование записи...
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group row">
	                        <label for="user__mod_login" class="col-sm-4 col-form-label text-sm-right font-bold">
	                            Тема:
	                        </label>
	                        <div class="col-sm-8 userprofile__item">
	                            <input type="text" class="form-control-plaintext" v-model="blogArr.title">
	                            <div class="col-sm-1 userprofile__edit">
	                                <i class="fas fa-edit fa-lg"/>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <label for="user__mod_login" class="col-sm-4 col-form-label text-sm-right font-bold">
	                            Текст:
	                        </label>
	                        <div class="col-sm-8 userprofile__item">
	                            <textarea type="text" class="form-control-plaintext p-2" rows="3" v-model="blogArr.text" placeholder="..."/>
	                            <div class="col-sm-1 userprofile__edit">
	                                <i class="fas fa-edit fa-lg"/>
	                            </div>
	                        </div>
	                    </div>
					</div>
					<div class="modal-footer ta-center">
						<button class="btn btn-primary no-round flex-sm-fill" @click="editItem()">
		                    Подтвердить
		                </button>
		                <button class="btn btn-danger no-round flex-sm-fill" data-dismiss="modal">
		                    Отмена
		                </button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="removeBlogItemModal" tabindex="-1" role="dialog" aria-labelledby="removeBlogItemTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title font-up font-bold" id="addInArchiveTitle">
						Удалить запись
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row">
                        <label for="user__mod_login" class="col-sm-4 col-form-label text-sm-right font-bold">
                            Тема:
                        </label>
                        <div class="col-sm-8 userprofile__item">
                            <input type="text" class="form-control-plaintext" v-model="blogArr.title" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user__mod_login" class="col-sm-4 col-form-label text-sm-right font-bold">
                            Дата создания:
                        </label>
                        <div class="col-sm-8 userprofile__item">
                            <input type="text" class="form-control-plaintext" v-model="blogArr.created_at" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user__mod_login" class="col-sm-4 col-form-label text-sm-right font-bold">
                            Текст:
                        </label>
                        <div class="col-sm-8 userprofile__item">
                            <textarea type="text" class="form-control-plaintext p-2" rows="3" v-model="blogArr.text" placeholder="..." disabled/>
                        </div>
                    </div>
				</div>
				<div class="modal-footer ta-center">
					<button class="btn btn-danger no-round flex-sm-fill" @click="removeItem()">
	                    Удалить
	                </button>
	                <button class="btn btn-secondary no-round flex-sm-fill" data-dismiss="modal">
	                    Отмена
	                </button>
				</div>
			</div>
		</div>
	</div>
	</div>
</template>
<script>
	export default {
		props: {
			userId: String,
		},
		data() {
			return {
				blogItems: [],
				userMessage: 0,
				newItem: {
					title: '',
					text: '',
				},
				blogArr: {
					id: null,
					title: '',
					text: '',
				}
			}
		},
		created() {
			this.$root.checkRole(this.userId);
		},
		mounted() {
			this.loadBlog();
		},
		methods: {
			loadBlog: function() {
				axios.post('api/getblogitems', {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.blogItems = response.data.result;
							console.log(this.blogItems);
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
			createBlogItem: function() {
				axios.post('api/addblog', this.newItem, {
					headers: {
						'Content-Type': "application/json"
					}
				}).then(response => {
						if (response.data.error == 0) {
							this.newItem = {
								title: '',
								text: '',
							};
							this.userMessage = 1;
							this.loadBlog();
						} else {
							this.userMessage = 2;
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						this.userMessage = 2;
						console.log(error);
					});
			},
			editItem: function() {
				axios.post('api/updateblogitem', this.blogArr, {
					headers: {
						'Content-Type': "application/json"
					}
				}).then(response => {
						if (response.data.error == 0) {
							// this.loadBlog();
							this.$router.go();
						} else {
							alert('Ошибка получения данных');
							this.$router.go();
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						this.userMessage = 2;
						console.log(error);
					});
			},
			removeItem: function() {
				let data = {};
				data = this.blogArr;
				data.delete = 1;
				axios.post('api/updateblogitem', data, {
					headers: {
						'Content-Type': "application/json"
					}
				}).then(response => {
						if (response.data.error == 0) {
							// this.loadBlog();
							this.$router.go();
						} else {
							alert('Ошибка получения данных');
							this.$router.go();
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						this.userMessage = 2;
						console.log(error);
					});
			},
			getBlogItemInfo: function(n) {
				this.blogArr.id = this.blogItems[n].id;
				this.blogArr.title = this.blogItems[n].title;
				this.blogArr.text = this.blogItems[n].text;
				this.blogArr.created_at = this.$root.frmtDate(this.blogItems[n].created_at);
			},
			closeMsg: function() {
				this.userMessage = 0;
			},
		}
	}
</script>