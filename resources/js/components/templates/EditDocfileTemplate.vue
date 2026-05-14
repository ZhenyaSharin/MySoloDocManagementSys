<template>
	<div v-if="mode==false">
		<button class="btn btn-secondary no-round font-bold btn-shad wide_btn my-2" @click="toggleEditFile()">
			<i class="fas fa-edit fa-lg"/>&nbsp;Редактировать файл
		</button>
	</div>
	<div v-else>
		<div class="card my-2">
			<div class="card-header card-header_custom">
				<span class="font-up font-bold">
					Редактирование файла
				</span>
				<div class="newuser_close shad-hover" @click="toggleEditFile()">
					<i class="fas fa-times fa-lg"/>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered my-1">
					<tbody>
						<tr>
							<th class="td_light-grey" scope="row">
								Новый файл документа:
							</th>
							<td>
								<div class="d-flex align-items-center">
		                			<div class="custom-file">
		                				<input type="file" class="custom-file-input cursor-point" ref="file" id="customFile" @change="uploadFile()">
										<label class="custom-file-label" for="customFile" data-browse="Обзор файлов">Выберите файл</label>
		                			</div>
									<div v-if="deleteFileSign==true" title="Удалить файл" class="cursor-point p-2" @click="clearFile()">
										<i class="fas fa-times fa-lg"/>
									</div>
								</div>
								<small class="form-text text-muted greytxt ml-1">
									* Файл или архив файлов.
								</small>
								<div class="invalid-feedback">
									Возникла ошибка с загрузкой файла
								</div>
							</td>
<!-- 							<td v-else>
								<a v-if="$root.storage=='coresar'" :href="'/storage/app/public/pdfs/'+ $parent.start.docFile.title+ '.' +$parent.start.docFile.format">
			                			{{ $parent.start.docFile.title + '.' + $parent.start.docFile.format }}
		                		</a>
		                		<a v-else :href="'/storage/pdfs/'+ $parent.start.docFile.title+ '.' +$parent.start.docFile.format">
		                			{{ $parent.start.docFile.title + '.' + $parent.start.docFile.format }}
		                		</a>
		                		<div title="Удалить файл" class="cursor-point p-2" @click="toggleOldFile()">
									<i class="fas fa-times fa-lg"/>
								</div>
							</td> -->
						</tr>
					</tbody>
				</table>
				<div class="wide_btn">
					<button v-if="$parent.editFile==null" class="btn btn-danger no-round font-bold box-shad mt-2"  style="width: inherit;" disabled>
						<i class="fas fa-save fa-lg"/>&nbsp;&nbsp;Сохранить изменения
					</button>
					<button v-else class="btn btn-danger no-round font-bold box-shad mt-2" data-toggle="modal" data-target="#saveDocFileModal" style="width: inherit;">
						<i class="fas fa-save fa-lg"/>&nbsp;&nbsp;Сохранить изменения
					</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			oldFile: Boolean,
		},
		data() {
			return {
				deleteFileSign: false,
				mode: false,
				file: null,
				// docFile: null,
			}
		},
		mounted() {
			this.file = this.oldFile;
		},
		methods: {
			toggleEditFile: function() {
				this.mode = (this.mode === false) ? true : false;
			},
			clearFile: function() {
				this.$parent.editFile = null;
				document.querySelector('.custom-file-label').innerHTML = 'Выберите файл';
				document.querySelector('.custom-file-label').value = '';
				this.deleteFileSign = false;
				console.log(this.$parent.editFile);
			},
			uploadFile: function() {
				console.log(this.file);
			    this.$parent.editFile = this.$refs.file.files[0];
			    if (this.$parent.editFile) {
			    	console.log(this.$parent.editFile);
			    	this.deleteFileSign = true;
			    	document.querySelector('.custom-file-label').innerHTML = this.$parent.editFile.name;
			    };
			},
			toggleOldFile: function() {
            	this.file = (this.file === true) ? false : true;
            	console.log(this.file);
            },
		},
	}	
</script>