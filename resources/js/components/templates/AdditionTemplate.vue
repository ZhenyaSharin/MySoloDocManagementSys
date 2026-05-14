<template>
	<div class="addition_tmplt d-flex flex-column p-2 p-md-4 my-1">
		<span class="font-bold">
			Файл(ы) приложения:
		</span>
		<br/>
		<!-- <template v-if="edit==false"> -->
		<div class="row" v-for="(item, index) in fileArr">
			<template v-if="edit==false">
				<div class="col-md-12 d-flex flex-column align-items-stretch mb-4">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<vue-link-docfile :link="'/storage/additions/'+ item.file" :title="item.file" :file-load="true" :file-format="item.format" :file-name="item.comment"/>
						</div>
						<div class="cursor-point" v-if="editList==true" style="width: 20px; color: red;" title="Удалить приложение" @click="">
							<i class="fas fa-trash fa-lg" @click="delFileItem(fileArr[index])" data-toggle="modal" data-target="#deleteAdditionFileModal" title="Удалить файл"/>
						</div>
					</div>
					<div class="d-flex justify-content-between align-items-center">
						<div class="align-self-stretch">
							<div class="greytxt">
								Наименование / Комментарий:
							</div>
							<div v-if="item.comment!=null" style="width: 100%" class="back_white p-1 pl-2">
								{{ item.comment}}
							</div>
							<div v-else style="width: 100%" class="back_white p-1 pl-2">
								...
							</div>
						</div>
						<div v-if="author===true" @click="editItemToggle(index)" class="font-bold cursor-point" style="width: 20px;">
							<i class="fas fa-edit fa-lg"/>
						</div>	
					</div>
				</div>	
			</template>		
			<template v-else-if="edit==true&&editItem==index">
				<table v-if="author===true" class="table mt-2">
					<tbody>
						<tr>
							<th class="ta-center" scope="row">
								{{ editItem+1 }}
							</th>
							<td>
								{{ fileArr[editItem]['file'] }}
							</td>
							<td class="ta-right cursor-point" @click="delFileItem(fileArr[index])" data-toggle="modal" data-target="#deleteAdditionFileModal" title="Удалить файл">
								<i class="fas fa-times fa-lg"/>
							</td>
						</tr>
						<td colspan="100%">
							<textarea class="form-control" required autocomplete="description" autofocus max="255" :placeholder="'Наименование / Комментарий (' + (index+1) +')'" rows="2" v-model="fileArr[editItem]['comment']"/>
						</td>
					</tbody>
				</table>
			</template>
		</div>
		<div v-if="author===true">
			<template v-if="editList==true">
				<div v-if="type=='doc'" class="row">
					<vue-template-newaddition :id="$parent.docData.id" :type="'doc'" :editList="true"/>
				</div>
				<div v-else-if="type=='assign'" class="row">
					<vue-template-newaddition :id="$parent.data.id" :type="'assign'" :editList="true"/>
				</div>
			</template>
			<div class="wide_btn" v-if="edit==false">
				<button v-if="editList==true" class="btn btn-warning font-bold wide_btn no-round mt-3" @click="editListToggle(false)">
					<i class="fas fa-times fa-lg"/>&nbsp;&nbsp;Закрыть редактирование 
				</button>
				<button v-else class="btn btn-info font-bold wide_btn no-round mt-3" @click="editListToggle(true)">
					<i class="fas fa-plus fa-lg"/>&nbsp;&nbsp;Редактировать список
				</button>
				<button class="btn btn-danger btn-shad font-bold wide_btn no-round mt-3" data-toggle="modal" data-target="#deleteAdditionModal">
					<i class="fas fa-trash fa-lg"/>&nbsp;&nbsp;Удалить всё
				</button>
			</div>
			<div v-else style="width: 100%;" class="d-flex flex-wrap justify-content-between align-items-stretch">
				<button v-if="isCreating==true" class="btn btn-primary btn-shad font-bold wide_btn no-round mt-3" disabled>
					<vue-spinner/>
				</button>
				<button v-else class="btn btn-primary btn-shad font-bold wide_btn no-round mt-3" @click="editAddition()">
					<i class="fas fa-save fa-lg"/>&nbsp;&nbsp;Сохранить
				</button>
				<button v-if="isCreating==true" class="btn btn-secondary btn-shad font-bold wide_btn no-round mt-3" disabled>
					<i class="fas fa-times fa-lg"/>&nbsp;&nbsp;Отменить
				</button>
				<button v-else  @click="editToggle(false)" class="btn btn-secondary btn-shad font-bold wide_btn no-round mt-3">
					<i class="fas fa-times fa-lg"/>&nbsp;&nbsp;Отменить
				</button>
			</div>
			<template>
				<vue-modal-deletefileaddition :data="currentFile"/>
			</template>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			data: Array,
			author: {
				type: Boolean,
				default: false,
			},
			type: {
				type: String,
				default: 'doc',
			},
		},
		data() {
			return {
				edit: false,
				comment: '',
				file: null,
				fileName: null,
				newFile: null,
				// newAdd: false,
				deleteFileSign: false,
				isCreating: false,
				currentFile: {
					id: null,
					file: null,
				},
				fileArr: [],
				editItem: null,
				editList: false,
			}
		},
		created() {
			this.comment = this.data.comment;
			this.makeFileArr();
		},
		methods: {
			editToggle: function() {
				this.edit = (this.edit == false) ? true : false;
				if (this.edit == false) {
					this.makeFileArr();
					this.isCreating = false;
					this.editItem = null;
				}
			},
			editItemToggle: function(n) {
				this.editToggle();
				this.editItem = n;
			},
			deleteAdditionFile: function() {
				this.fileName = null;
			},
			uploadFile: function() {
			    this.file = this.$refs.file.files[0];
			    this.deleteFileSign = true;
			    document.querySelector('.custom-file-label').innerHTML = this.file.name;
			},
			clearFile: function() {
				this.file = null;
				this.fileName = null;
				document.querySelector('.custom-file-label').innerHTML = 'Выберите файл';
				this.deleteFileSign = false;
			},
			editAddition: function() {
				let data = {
					id: this.fileArr[this.editItem]['id'],
					comment: this.fileArr[this.editItem]['comment'],
				}
				this.isCreating = true;
				axios.post('api/updatefilecomment', data, {
			        headers: {
			          "Content-Type": "application/json"
			        }
			    })
					.then(response => {
						if (response.data.error == 0) {
							this.userMessage = 1;
							this.isCreating = false;
							this.editItem = null;
							this.makeFileArr();
							this.edit==false;
							this.$router.go();
						} else {
							this.userMessage = 2;
							this.isCreating = false;
							this.editItem = null;
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						this.userMessage = 2;
						console.log(error);
						this.isCreating = false;
					});
			},
			makeFileArr: function() {
				this.fileArr = [];
				this.data.forEach(item => {
					this.fileArr.push({
						id: item.id,
						file: item.file + '.' + item.format,
						comment: item.comment,
						fileAdditionId: item.addFile.id,
						format: item.format,
					});
				});
			},
			delFileItem: function(item) {
				this.currentFile.id = item.fileAdditionId;
				this.currentFile.file = item.file;
			},
			editListToggle: function(n) {
				this.editList = n;
				this.$root.newAdd = n;
				// this.makeFileArr();
			},
		}, 
	}
</script>