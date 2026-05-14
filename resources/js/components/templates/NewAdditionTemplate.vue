<template>
	<div class="col-md-12">
		<br/>
    	<div class="alert alert-success d-flex justify-content-between align-items-center" v-if="userMessage==1">
	        <div>
	        	<i class="far fa-thumbs-up fa-lg"/>&nbsp;&nbsp;Приложение добавлено
	        </div>
	        <div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
	        	<i class="fas fa-times fa-lg"/>
	        </div>
	    </div>
	    <div class="alert alert-danger d-flex justify-content-between align-items-center" v-else-if="userMessage==2">
	    	<div>
	    		<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Возникла ошибка...
	    	</div>
	    	<div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
	        	<i class="fas fa-times fa-lg"/>
	        </div>
	    </div>
    	<div class="d-flex justify-content-between align-items-center" v-if="$root.newAdd==false">
    		<button @click="toggleAdd(true)" class="btn btn-addnew">
    			<template v-if="type=='doc'">
    				<i class="fas fa-plus fa-lg"/>&nbsp;&nbsp;Добавить приложение к карточке
    			</template>
    			<template v-else-if="type=='assign'">
    				<i class="fas fa-plus fa-lg"/>&nbsp;&nbsp;Добавить приложение к поручению
    			</template>
    			<template v-else>
    				<i class="fas fa-plus fa-lg"/>&nbsp;&nbsp;Добавить приложение
    			</template>
        	</button>
    	</div>
        <div v-else-if="$root.newAdd==true" class="card mt-4">
			<div class="card-header card-header_custom font-bold font-up">
				Создание приложения
			</div>
			<div v-if="editList==false" class="newuser_close shad-hover" @click="toggleAdd(false)">
				<i class="fas fa-times fa-lg"/>
			</div>
			<div class="card-body">
				<div class="d-flex align-items-center">
        			<div class="custom-file">
        				<input v-if="$root.newAdd==true" type="file" class="custom-file-input" ref="file" id="customFile" @change="uploadFile()" multiple>
        				<input v-else type="file" class="custom-file-input" ref="file" id="customFile" disabled>
						<label class="custom-file-label" for="customFile" data-browse="Обзор файлов">
							Выберите файл(ы)
						</label>
        			</div>
				</div>
				<table v-for="(item, index) in fileArr" class="table mt-4">
					<tbody>
						<tr>
							<th class="ta-center" scope="row">
								{{ index+1 }}
							</th>
							<td>
								{{ item.name }}
							</td>
							<td class="ta-right cursor-point" @click="delFileItem(index)">
								<i class="fas fa-times fa-lg"/>
							</td>
						</tr>
						<td colspan="100%">
							<textarea class="form-control" name="description" required autocomplete="description" autofocus max="255" :placeholder="'Наименование / Комментарий (' + (index+1) +')'" id="description" rows="3" v-model="comment[index]"/>
						</td>
					</tbody>
				</table>
				<div v-if="id!==null" class="form-group row mb-0 mt-4">
					<div v-if="file==null" class="col-md-12">
		                <button id="create__card" class="btn btn-primary font-bold no-round font-up py-2 wide_btn" disabled>
		                	Добавить
		                </button>
		            </div>
		            <div v-else class="col-md-12">
		            	<a v-if="isCreating==true" id="create__card" class="btn btn-primary font-bold no-round font-up py-1 box-shad wide_btn" disabled>
		                    <vue-spinner/>
		                </a>
		                <a v-else id="create__card" class="btn btn-primary font-bold no-round font-up py-2 box-shad wide_btn" @click="addAddition()">
		                	Добавить
		                </a>
		            </div>
		        </div>
		    </div>
		</div>
		<br/>
	</div>
</template>
<script>
	export default {
		props: {
			type: String,
			id: {
				type: Number,
				default: null,
			},
			editList: {
				type: Boolean,
				default: false,
			}
		},
		data() {
			return {
				isCreating: false,
				// newAdd: false,
				userMessage: 0,
				file: null,
				deleteFileSign: false,
				comment: [],
				fileArr: [],
			}
		},
		methods: {
			toggleAdd: function(n) {
				this.$root.newAdd = n;
				if (n == false) {
					this.closeMsg();
					this.clearFile();
					this.comment = [];
				}
			},
			uploadFile: function() {
				// this.fileArr = this.$refs.file.files;
				for (let i = 0; i < this.$refs.file.files.length; i++) {
					this.fileArr.push(this.$refs.file.files[i]);
				}
			    this.file = this.$refs.file.files[0];
			    this.deleteFileSign = true;
			    // document.querySelector('.custom-file-label').innerHTML = this.file.name;
			    // console.log(this.$refs.file.files);	
			},
			clearFile: function() {
				this.file = null;
				document.querySelector('.custom-file-label').innerHTML = 'Выберите файл';
				this.deleteFileSign = false;
			},
			addAddition: function() {
				this.isCreating = true;
				let data = new FormData();
				if (this.type == 'doc') {
					data.append('documentId', this.id);
				} else if (this.type == 'assign') {
					data.append('assignmentId', this.id);
				} else if (this.type == 'blog') {
					data.append('blogId', this.id);
				} else if (this.type == 'feedbackId') {
					data.append('feedbackId', this.id);
				} else if (this.type == 'agreementAndUserId') {
					data.append('agreementAndUserId', this.id);
				}
				let file = [];
				for (let i = 0; i < this.fileArr.length; i++) {
					console.log(this.comment[i]);
					this.comment[i] = (this.comment[i] !== undefined) ? this.comment[i] : '';
					data.append('files[]', this.$refs.file.files[i]);
					data.append('comment[]', this.comment[i]);
				}
				// data.append('comment', this.comment);
				console.log(data.get('files'));
				axios.post('api/addfileaddition', data, {
			        headers: {
			          'Content-Type': 'multipart/form-data'
			        }
			    })
					.then(response => {
						if (response.data.error == 0) {
							this.userMessage = 1;
							this.$root.newAdd = 0;
							this.isCreating = false;
							this.file = null;
							this.comment = null;
							this.$router.go();
						} else {
							this.userMessage = 2;
							this.isCreating = false;
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						this.userMessage = 2;
						console.log(error);
						this.isCreating = false;
					});
			},
			closeMsg: function() {
				this.userMessage = 0;
			},
			delFileItem: function(index) {
				console.log(this.fileArr);
				this.fileArr.splice(index, 1);
			},
			getChildRef() {
				if (this.fileArr.length > 0) {
					return this.$refs.file.files;
				}	
				return false;
		    },
		}
	}
</script>