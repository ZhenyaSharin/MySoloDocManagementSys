<template>
	<div class="col-md-12">
    	<div class="alert alert-success d-flex justify-content-between align-items-center" v-if="userMessage==1">
	        <div>
	        	<i class="far fa-thumbs-up fa-lg"/>&nbsp;&nbsp;Вы успешно создали новую карточку документа
	        </div>
	        <div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
	        	<i class="fas fa-times fa-lg"/>
	        </div>
	    </div>
	    <div class="alert alert-danger d-flex justify-content-between align-items-center" v-else-if="userMessage==2">
	    	<div>
	    		<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Не удалось создать новую карточку документа, возникла ошибка. Проверьте правильность заполняемых данных или формата файла...
	    	</div>
	    	<div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
	        	<i class="fas fa-times fa-lg"/>
	        </div>
	    </div>
    	<div class="d-flex justify-content-between align-items-center" v-if="newDoc===0">
    		<button @click="initDoc()" class="btn btn-addnew">
    			<template v-if="start==null">
    				<i class="fas fa-plus fa-lg"/>&nbsp;&nbsp;Создать карточку документа
    			</template>
    			<template v-else>
    				<i class="fas fa-copy"/>&nbsp;&nbsp;Создать карточку документа на основе данной карточки
    			</template>
        	</button>
    	</div>
        <div v-else-if="newDoc===1" class="card mt-4">
			<div class="card-header card-header_custom font-bold font-up">
				Создание карточки документа
			</div>
			<div class="newuser_close shad-hover" @click="closeIt()">
				<i class="fas fa-times fa-lg"/>
			</div>
			<div class="card-body card-body_custom">
				<vue-alert-inwork/>
		        <div class="row">
		        	<div class="col-12 col-lg-6">
		        		<div class="form-group row">
		            		<label for="department" class="col-md-4 col-form-label text-md-right">
		            			Тип документа<span class="alert-red">&nbsp;*</span>
		            		</label>
			            	<div class="col-md-8">
		                        <vue-multiselect v-model="docType" :options="$root.documentTypes" placeholder="Выберите тип" label="title" track-by="id" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано" :searchable="false"/>
		                    </div>
		            	</div>
		            	<div class="form-group row">
		                    <label for="description" class="col-md-4 col-form-label text-md-right">
		                    	Описание документа<span class="alert-red">&nbsp;*</span>
		                    </label>
		                    <div class="col-md-8">
		                        <textarea v-if="docType!=null" class="form-control" name="description" required autocomplete="description" autofocus maxlength="512" placeholder="Описание документа (макс. 512 символов)" id="description" rows="5" v-model="docDesc"/>
		                        <textarea v-else class="form-control" name="description" autofocus maxlength="512" placeholder="Описание документа" id="description" rows="5" disabled/>
		                    </div>
		                </div>
		                <div class="form-group row">
		                	<label for="department" class="col-md-4 col-form-label text-md-right">
		                		Файл документа<span class="alert-red">&nbsp;*</span>
		                	</label>
		                	<!-- <div class="col-md-8" v-if="start==null"> -->
		                	<div class="col-md-8">
		                		<div class="d-flex align-items-center">
		                			<div class="custom-file">
		                				<input v-if="docType!=null" type="file" class="custom-file-input" ref="file" id="customFile" @change="uploadFile()" required>
		                				<input v-else type="file" class="custom-file-input" ref="file" id="customFile" disabled>
										<label class="custom-file-label" for="customFile" data-browse="Обзор файлов">
											Выберите файл
										</label>
		                			</div>
									<div v-if="deleteFileSign==true" title="Удалить файл" class="cursor-point p-2" @click="clearFile()">
										<i class="fas fa-times fa-lg"/>
									</div>
								</div>
							<!-- 	<small class="form-text text-muted greytxt ml-1">
									* Файлы Word (doc, docx) или PDF.
								</small> -->
								<small class="form-text text-muted greytxt ml-1">
									* Файл или архив файлов.
								</small>
								<div class="invalid-feedback">
									Возникла ошибка с загрузкой файла
								</div>
		                	</div>
<!-- 		                	<div class="col-md-8" v-else>
		                		<div v-if="oldFile==true" class="d-flex justify-content-between align-items-center">
			                		<vue-link-docfile :link="'/storage/pdfs/'+ start.docFile.title+ '.' +start.docFile.format" :title="start.docFile.title + '.' + start.docFile.format"/>
			                		<div title="Удалить файл" class="cursor-point p-2" @click="toggleOldFile()">
										<i class="fas fa-times fa-lg"/>
									</div>
								</div>
								<div v-else class="d-flex align-items-center">
									<div class="custom-file">
		                				<input v-if="docType!=null" type="file" class="custom-file-input" ref="file" id="customFile" @change="uploadFile()">
		                				<input v-else type="file" class="custom-file-input" ref="file" id="customFile" disabled>
										<label class="custom-file-label" for="customFile" data-browse="Обзор файлов">
											Выберите файл
										</label>
		                			</div>
									<div v-if="deleteFileSign==true" title="Удалить файл" class="cursor-point p-2" @click="clearFile()">
										<i class="fas fa-times fa-lg"/>
									</div>
									<div v-else title="Вернуть файл карточки-прототипа" class="cursor-point p-2" @click="toggleOldFile()">
										<i class="fas fa-redo fa-lg"/>
									</div>
								</div>
		                	</div> -->
		                </div>
		                <div class="form-group row">
		                	<template v-if="docType!=null">
		                		<label v-if="docType.id==2" for="docNum" class="col-md-4 col-form-label text-md-right">
			                		Номер договора
			                	</label>
			                	<label v-else-if="docType.id==6" for="docNum" class="col-md-4 col-form-label text-md-right">
			                		Входящий номер
			                	</label>
			                	<label v-else-if="docType.id==7" for="docNum" class="col-md-4 col-form-label text-md-right">
			                		Номер исходящего письма
			                	</label>
			                	<label v-else-if="docType.id==9" for="docNum" class="col-md-4 col-form-label text-md-right">
			                		Номер приказа по ОД
			                	</label>
			                	<label v-else-if="docType.id==12" for="docNum" class="col-md-4 col-form-label text-md-right">
			                		Номер уведомления
			                	</label>
			                    <label v-else for="docNum" class="col-md-4 col-form-label text-md-right">
			                    	Номер документа
			                    </label>
		                	</template>
		                	<template v-else>
		                		<label for="docNum" class="col-md-4 col-form-label text-md-right">
			                    	Номер документа
			                    </label>
		                	</template>
		                    <div class="col-md-8">
		                        <input v-if="docType!=null" type="text" class="form-control" name="docNum" autocomplete="docNum" v-model="docNum" id="docNum" maxlength="64">
		                        <input v-else type="text" class="form-control" name="docNum" autocomplete="docNum" id="docNum" disabled>
		                    </div>
		                </div>
		                <div class="form-group row">
<!-- 		                	<template v-if="docType!=null">
		                		<label v-if="docType.id==2" for="docDate" class="col-md-4 col-form-label text-md-right">
			                		Дата договора
			                	</label>
			                	<label v-else-if="docType.id==6" for="docDate" class="col-md-4 col-form-label text-md-right">
			                		Дата получения письма
			                	</label>
			                	<label v-else-if="docType.id==7" for="docDate" class="col-md-4 col-form-label text-md-right">
			                		Дата отправления
			                	</label>
			                	<label v-else-if="docType.id==9" for="docDate" class="col-md-4 col-form-label text-md-right">
			                		Дата создания приказа
			                	</label>
			                	<label v-else-if="docType.id==12" for="docDate" class="col-md-4 col-form-label text-md-right">
			                		Дата создания
			                	</label>
			                    <label v-else for="docDate" class="col-md-4 col-form-label text-md-right">
			                    	Дата документа
			                    </label>
		                	</template>
		                	<template v-else> -->
		                		<label for="docDate" class="col-md-4 col-form-label text-md-right">
			                    	Дата документа<span class="alert-red">&nbsp;*</span>
			                    </label>
		                	<!-- </template> -->
		                    <div class="col-md-8">
		                        <vue-datepicker v-if="docType!=null" id="docDate" v-model="docDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" style="width: inherit;" placeholder="ДД.ММ.ГГГГ" required/>
		                        <vue-datepicker v-else id="docDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" style="width: inherit;" placeholder="ДД.ММ.ГГГГ" disabled/>
		                    </div>
		                </div>
		            	<div class="form-group row">
			            	<div class="col-md-8 offset-md-4">
		                    	<small class="form-text text-muted greytxt ml-1">
									<span class="alert-red">*&nbsp;</span> Обязательные для заполнения поля.
								</small>  
		                    </div>
		            	</div>
		                <template v-if="docType!=null">
		                	<div v-if="docType.id==2">
		                		<div class="form-group row">
				                	<div class="offset-md-4 col-md-8 mt-1 mb-2">
				                		<h5>
						              	 	Дополнительные данные:
						                </h5>
				                	</div>	
				                </div>
				                <div class="form-group row">
				                    <label for="contractTitle" class="col-md-4 col-form-label text-md-right">
				                    	Наименование
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="contractTitle" autocomplete="contractTitle" v-model="docContractTitle">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="contractDeadline" class="col-md-4 col-form-label text-md-right">
				                    	Дата закрытия
				                    </label>
				                    <div class="col-md-8">
				                        <vue-datepicker id="contractDeadline" v-model="docContractDeadline" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="contractCo" class="col-md-4 col-form-label text-md-right">
				                    	Соисполнитель
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="contractCo" autocomplete="contractCo" v-model="docContractCo">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="contractShortName" class="col-md-4 col-form-label text-md-right">
				                    	Краткое наименование
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="contractShortName" autocomplete="contractShortName" v-model="docContractShortName">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="contractSum" class="col-md-4 col-form-label text-md-right">
				                    	Сумма по договору
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="contractSum" autocomplete="contractSum" v-model="docContractSum">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="contractSteps " class="col-md-4 col-form-label text-md-right">
				                    	Этапы
				                    </label>
				                    <div class="col-md-8">
				                        <textarea class="form-control" name="contractSteps"  autocomplete="contractSteps" maxlength="512" placeholder="Введите этапы договора (макс. 512 символов)" id="contractsteps" rows="4" v-model="docContractSteps"/>
				                    </div>
				                </div>
							</div>
							<div v-if="docType.id==6">
								<div class="form-group row">
				                	<div class="offset-md-4 col-md-8">
				                		<h5>
						              	 	Дополнительные данные:
						                </h5>
				                	</div>	
				                </div>
				                <div class="form-group row">
				                    <label for="inLetterOuterNum" class="col-md-4 col-form-label text-md-right">
				                    	Номер у отправителя
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="inLetterOuterNum" id="inLetterOuterNum" autocomplete="inLetterOuterNum" v-model="docInLetterOuterNum">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="inLetterOuterDate" class="col-md-4 col-form-label text-md-right">
				                    	Дата отправления
				                    </label>
				                    <div class="col-md-8">
				                        <vue-datepicker id="inLetterOuterDate" v-model="docInLetterOuterDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="inLetterNotice" class="col-md-4 col-form-label text-md-right">
				                    	Примечание
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="inLetterNotice" id="inLetterNotice" autocomplete="inLetterNotice" v-model="docInLetterNotice">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="inLetterDeadline" class="col-md-4 col-form-label text-md-right">
				                    	Срок Исполнения
				                    </label>
				                    <div class="col-md-8">
				                        <vue-datepicker id="inLetterDeadline" v-model="docInLetterDeadline" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
				                    </div>
				                </div>
		                	</div>
		                	<div  v-if="docType.id==7">
		                		<div class="form-group row">
				                	<div class="offset-md-4 col-md-8">
				                		<h5>
						              	 	Дополнительные данные:
						                </h5>
				                	</div>	
				                </div>
				                <div class="form-group row">
				                    <label for="outcomingLetterOuterNum" class="col-md-4 col-form-label text-md-right">
				                    	Номер у получателя
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="outcomingLetterOuterNum" id="outcomingLetterOuterNum"  autocomplete="outcomingLetterOuterNum" v-model="docOutcomingLetterOuterNum">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="outcomingLetterOuterDate" class="col-md-4 col-form-label text-md-right">
				                    	Дата получения
				                    </label>
				                    <div class="col-md-8">
				                        <vue-datepicker id="outcomingLetterOuterDate" v-model="docOutcomingLetterOuterDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="outcomingLetterExecutor" class="col-md-4 col-form-label text-md-right">
				                    	Кому на исполнение
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="outcomingLetterExecutor" id="outcomingLetterExecutor"  autocomplete="outcomingLetterExecutor" v-model="docOutcomingLetterExecutor">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="outcomingLetterNotice" class="col-md-4 col-form-label text-md-right">
				                    	Примечание
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="outcomingLetterNotice" id="outcomingLetterNotice" autocomplete="outcomingLetterNotice" v-model="docOutcomingLetterNotice">
				                    </div>
				                </div>
		                	</div>
		                	<div v-if="docType.id==8">
		                		<div class="form-group row">
				                	<div class="offset-md-4 col-md-8">
				                		<h5>
						              	 	Дополнительные данные:
						                </h5>
				                	</div>	
				                </div>
		                		<div class="form-group row">
				                    <label for="innerIncomingLetterName" class="col-md-4 col-form-label text-md-right">
				                    	Корреспондент
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="innerIncomingLetterName" id="innerIncomingLetterName"  autocomplete="innerIncomingLetterName" v-model="docInIncomingName">
				                    </div>
				                </div>
		                	</div>
		                	<div v-if="docType.id==9">
		                		<div class="form-group row">
				                	<div class="offset-md-4 col-md-8">
				                		<h5>
						              	 	Дополнительные данные:
						                </h5>
				                	</div>	
				                </div>
				                <div class="form-group row">
				                    <label for="odExecutor" class="col-md-4 col-form-label text-md-right">
				                    	Исполнитель
				                    </label>
				                    <div class="col-md-8">
				                    	<vue-multiselect v-model="docOdExecutor" :options="$root.usersList" :multiple="false" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано">
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
		                	<div v-if="docType.id==10">
		                		<div class="form-group row">
				                	<div class="offset-md-4 col-md-8">
				                		<h5>
						              	 	Дополнительные данные:
						                </h5>
				                	</div>	
				                </div>
		                	</div>
		                	<div v-if="docType.id==11">
		                		<div class="form-group row">
				                	<div class="offset-md-4 col-md-8">
				                		<h5>
						              	 	Дополнительные данные:
						                </h5>
				                	</div>	
				                </div>
		                		<div class="form-group row">
				                    <label for="attorneyNum" class="col-md-4 col-form-label text-md-right">
				                    	Номер доверенности
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="attorneyNum" id="attorneyNum" autocomplete="attorneyNum" v-model="docAttorneyNum">
				                    </div>
				                </div>
		                		<div class="form-group row">
				                    <label for="attorneyDate" class="col-md-4 col-form-label text-md-right">
				                    	Дата выдачи
				                    </label>
				                    <div class="col-md-8">
				                        <vue-datepicker id="attorneyDate" v-model="docAttorneyDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="attorneyValidity" class="col-md-4 col-form-label text-md-right">
				                    	Срок действия
				                    </label>
				                    <div class="col-md-6">
				                        <vue-datepicker id="attorneyValidity" v-model="docAttorneyValidity" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="attorneyFio" class="col-md-4 col-form-label text-md-right">
				                    	ФИО кому выдано
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="attorneyFio" id="attorneyFio" autocomplete="attorneyFio" v-model="docAttorneyFio">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="attorneyPosition" class="col-md-4 col-form-label text-md-right">
				                    	Должность
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="attorneyPosition" id="attorneyPosition" autocomplete="attorneyPosition" v-model="docAttorneyPosition">
				                    </div>
				                </div>
				                <div class="form-group row">
				                	<label for="attorneyType" class="col-md-4 col-form-label text-md-right">
				                		Вид
				                	</label>
				                    <div class="col-md-8">
				                    	<div class="input-group mb-3">
						                    <div class="input-group-prepend">
												<div class="input-group-text">
													<input name="attorneyType" id="attorneyType" type="checkbox" v-model="docAttorneyType">
												</div>
											</div>
											<!-- <input type="text" class="form-control" placeholder="С правом передоверия..." disabled> -->
											<span class="p-1 pl-3">С правом передоверия...</span>
										</div>
									</div>
				                </div>
		                	</div>
		                	<div v-if="docType.id==12">
		                		<div class="form-group row">
				                	<div class="offset-md-4 col-md-8">
				                		<h5>
						              	 	Дополнительные данные:
						                </h5>
				                	</div>	
				                </div>
				                <div class="form-group row">
				                    <label for="notifSign" class="col-md-4 col-form-label text-md-right">
				                    	Подписант
				                    </label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="notifSign" id="notifSign"  autocomplete="notifSign" v-model="docNotifSign">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="notifAuthor" class="col-md-4 col-form-label text-md-right">Автор:</label>
				                    <div class="col-md-8">
				                        <input type="text" class="form-control" name="notifAuthor" id="notifAuthor" autocomplete="notifAuthor" v-model="docNotifAuthor">
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="notifAsq" class="col-md-4 col-form-label text-md-right">
				                    	Дата ознакомления
				                    </label>
				                    <div class="col-md-8">
				                        <vue-datepicker id="notifAsq" v-model="docNotifAsq" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
				                    </div>
				                </div>
		                	</div>
		                	<div v-if="docType.id==14||docType.id==15">
		                		<div class="form-group row">
				                	<div class="offset-md-4 col-md-8">
				                		<h5>
						              	 	Дополнительные данные:
						                </h5>
				                	</div>	
				                </div>
		                	</div>
		                </template>
		                <template v-if="docType!=null">
		                	<vue-template-diruseradd v-if="docType.id==2||docType.id==6||docType.id==7||docType.id==14||docType.id==15" :doc-type="docType.id"/>
		                </template>
		        	</div>
		        	<div v-if="docType!=null" class="col-12 col-lg-6">
<!-- 		        		<vue-template-agreers v-if="docType.id==6" :users-list="usersList" :doc-type="docType"/>
		        		<vue-template-agreers v-else :users-list="usersList" :doc-type="docType"/> -->
		        		<vue-template-agreers :users-list="$root.usersList" :doc-type="docType"/>
		        		<vue-template-basedocassign v-if="start==null" type="1"/>
		        		<vue-template-basedocassign v-else :opened="true" type="1" :base-tab="1"/>
		               	<vue-alert-docassignselect/>
   		                <vue-template-newaddition ref="newAddition" :type="'doc'" class="wide_btn p-0"/>
		            </div>
		            <div v-else class="col-md-6">
	                	
	                </div>
		        </div>
				<div class="form-group row mb-0">
		            <div class="col-md-12 d-flex justify-content-center" v-if="docType!=null">
		            	<a v-if="isCreating==true" id="create__card" class="btn btn-primary btn__creation_doc font-bold no-round font-up py-1 py-sm-3 box-shad" disabled>
		                    <vue-spinner/>
		                </a>
		                <a v-else id="create__card" class="btn btn-primary btn__creation_doc font-bold no-round font-up py-1 py-sm-3 box-shad" @click="addNewDoc()">
		                    Создать карточку документа
		                </a>
		            </div>
		            <div v-else class="col-md-12 d-flex justify-content-center">
		            	<a id="create__card" class="btn btn-primary btn__creation_doc font-bold no-round font-up py-1 py-sm-3" disabled>
		                    Создать карточку документа
		                </a>
		            </div>
		            <br/>
		        </div>
		    </div>
		</div>
		<br/>
	</div>
</template>
<script>
	export default {
		props: {
			userId: Number,
			start: Object,
			typeId: {
				type: Number,
				default: null,
			},
			docCount: {
				default: null,
				type: Number,
			},
		},
		data(){
			return {
				userMessage: 0,
				newDoc: 0,
				newApp: 0,
				docType: null,
				docDesc: '',
				docFile: '',
				// docInnerNum: null,
				docDepart: null,
				docDelivery: null,
				docReg: this.userId,
				docAgree: [], //
				deleteFileSign: false,
				// basetabPanel: false,
				// angleBase: 'basetab__angle',
				baseDoc: null,
				baseAssign: null,
				// baseNote: null,
				// basetabPanelType: 1,
				docsWithout: [],
				docAgreeItem: null,
				withoutAgr: null,
				deadline: null,
				agreementType: 1,
				baseData: {
					docId: '',
					assignId: '',
				},
				agree: [],
				diruser: null,
				newDiruser: false,
				newDiruserArr: {
					surname: null,
					firstname: null,
					patronymic: null,
				},
				withoutDiruser: false,
				isCreating: false,
				deliveryTypes: [],
				docContractTitle: '',
				docContractDeadline: null,
				docContractCustomer: '',
				docContractCo: '',
				docContractShortName: '',
				docContractSum: '',
				docContractSteps: '',
				docInLetterNotice: '',
				docInLetterDeadline: null,
				docInLetterOuterNum: null,
				docInLetterOuterDate: null,
				docOutcomingLetterName: '',
				docOutcomingLetterExecutor: '',
				docOutcomingLetterOuterNum: null,
				docOutcomingLetterOuterDate: null,
				docOutcomingLetterNotice: '',
				docInIncomingShort: '',
				docInIncomingName: '',
				docOdExecutor: null,
				docAttorneyNum: '',
				docAttorneyDate: null,
				docAttorneyValidity: null,
				docAttorneyFio: '',
				docAttorneyPosition: '',
				docAttorneyType: false,
				docNotifSign: '',
				docNotifAuthor: '',
				docNotifAsq: null,
				docDate: null,
				docNum: null,
				deadline: null,
				lang: {
		          	formatLocale: {
		            	firstDayOfWeek: 1,
		          	},
		        },
		        oldFile: false,
		        agreeValue: [],
		        withOut: false,
			}
		},
		mounted() {
			this.$root.getUsersList();
			this.docAgree.push(this.docAgreeItem);
		},
		methods: {
			initDoc: function() {
				this.$root.getDirusers();
				this.$root.getDocumentTypes();
				this.getDeliveryTypes();
				this.newDoc = 1;
				if (this.start != null) {
					this.docType = this.start.docType;
					this.docDesc = this.start.docDesc;
					this.diruser = this.start.dirusers;
					this.baseData.assignId = this.start.baseData.assignId;
					this.baseData.docId = this.start.baseData.docId;
					this.docNum = this.start.docNum;
					this.fileId = this.start.docFile.id;
					this.oldFile = true;
					console.log(this.start);
					this.$root.baseDocId = {
						id: this.$parent.docData.id,
						author: this.$parent.docData.authorData,
						description: this.$parent.docData.description,
						orderNum: this.$parent.docData.orderNum,
						creationDate: this.$parent.docData.creationDate,
						created_at: this.$parent.docData.created_at,
					};
				};
				// this.$root.assignsAll();
				// this.$root.docsAll(true);
				// console.log(this.$root.docsListAll);
			},
			closeIt: function() {
				// if (this.start == null) {
					this.clearFile();
				// };
				this.newDoc = 0;
				this.userMessage = 0;
				this.docType = null;
				this.refreshInfoDoc();
			},
			selectBase(n) {
				this.basetabPanelType = n;
			},
			addNewDoc: function() {
				this.isCreating = true;
				let agarr = [];
				if (this.withoutAgr !== 0) {
					this.agreeValue.forEach(item => {
						agarr.push({id: item.id})
					});
				};
				// this.baseData.docId = (this.$root.baseDocId != null) ? this.$root.baseDocId.id : '';
				if ((this.$root.baseDocId != null) || (this.$root.baseRefusedDocId != null)) {
					this.baseData.docId = (this.$root.baseDocId != null) ? this.$root.baseDocId.id : this.$root.baseRefusedDocId.id; 
				};
				this.baseData.assignId = (this.$root.baseAssignId != null) ? this.$root.baseAssignId.id : '';
				let data = new FormData();
				data.append('description', this.docDesc);
				data.append('authorId', this.userId);
				if (this.oldFile === true) {
					data.append('fileId', this.fileId);
				} else {
					data.append('file', this.docFile);
				};
				data.append('departmentId', 1);
				data.append('deliveryId', 1);
				data.append('recorderId', this.docReg);
				data.append('agreeId', JSON.stringify(agarr));
				data.append('baseId', this.baseData.docId);
				data.append('baseAssignmentId', this.baseData.assignId);
				data.append('typeId', this.docType.id);
				if (this.docDate != null) {
					data.append('creationDate', this.$root.frmtDateIn(this.docDate));
				}
				if (this.docNum != null) {
					data.append('orderNum', this.docNum);
				}
				if (this.deadline != null) {
					data.append('deadline', this.$root.frmtDateIn(this.deadline, '18:00:00'));
				}
				if (this.withoutAgr == 2) {
					data.append('orderable', 1);
				} 
				
				// адресаты контрагенты:
				if (this.docType.id == 2||this.docType.id == 6||this.docType.id == 7||this.docType.id == 14||this.docType.id == 15) {
					if (this.withoutDiruser == false) {
						if (this.newDiruser == true) {
							data.append('diruser', JSON.stringify(this.newDiruserArr));
						} else {
							data.append('diruser', JSON.stringify(this.diruser));
						}
					} else {
						data.append('diruser', null);
					}
				};

				if (this.docType.id == 2) {
					data.append('name', this.docContractTitle);
					if (this.docContractDeadline) {
						data.append('closeDate', this.$root.frmtDateIn(this.docContractDeadline));
					}
					data.append('customer', this.docContractCustomer);
					data.append('coExecutor', this.docContractCo);
					data.append('colName', this.docContractShortName);
					data.append('sumContract', this.docContractSum);
					data.append('phases', this.docContractSteps);
				} else if (this.docType.id == 6) {
					data.append('note', this.docInLetterNotice);
					if (this.docInLetterDeadline != null) {
						data.append('closeDate', this.$root.frmtDateIn(this.docInLetterDeadline));
					}
					if (this.docInLetterOuterNum != null) {
						data.append('outerNum', this.docInLetterOuterNum);
					}
					if (this.docInLetterOuterDate != null) {
						data.append('outerDate', this.$root.frmtDateIn(this.docInLetterOuterDate));
					}
				} else if (this.docType.id == 7) {
					data.append('addresser', this.docOutcomingLetterName);
					data.append('letterExecutor', this.docOutcomingLetterExecutor);
					if (this.docOutcomingLetterOuterNum != null) {
						data.append('outerNum', this.docOutcomingLetterOuterNum);
					}
					if (this.docOutcomingLetterOuterDate != null) {
						data.append('outerDate', this.$root.frmtDateIn(this.docOutcomingLetterOuterDate));
					}
					data.append('note', this.docOutcomingLetterNotice);
				} else if (this.docType.id == 8) {
					data.append('colName', this.docInIncomingName);
				} else if (this.docType.id == 9) {
					if (this.docOdExecutor != null) {
						data.append('executor', this.docOdExecutor.id);
					}
				} else if (this.docType.id == 10) {
				} else if (this.docType.id == 12) {
					data.append('signatory', this.docNotifSign);
					data.append('author', this.docNotifAuthor);
					if (this.docNotifAsq != null) {
						data.append('acqDate', this.$root.frmtDateIn(this.docNotifAsq));
					}
				}
				if (this.$refs.newAddition.getChildRef() != false) {
					// console.log(this.$refs.newAddition.getChildRef());
					for (let i = 0; i < this.$refs.newAddition.fileArr.length; i++) {
						console.log(i);
						this.$refs.newAddition.comment[i] = (this.$refs.newAddition.comment[i] !== undefined) ? this.$refs.newAddition.comment[i] : '';
						data.append('addFiles[]', this.$refs.newAddition.getChildRef()[i]);
						data.append('addComments[]', this.$refs.newAddition.comment[i]);
						console.log(this.$refs.newAddition.getChildRef()[i]);
					}
					console.log(data.get('addFiles'));
				} else {
					console.log('нет');
				}
				axios.post('api/newdocument', data, {
			        headers: {
			          'Content-Type': 'multipart/form-data'
			        }
			     })
					.then(response => {
						if (response.data.error == 0) {
							this.userMessage = 1;
							this.newDoc = 0;
							if (this.typeId != null) {
								this.$root.refreshPage();
							} else {
								if (this.start == null) {
								this.isCreating = true;
									if (this.docCount != null) {
										this.$root.getDocs(this.userId, this.docCount);
										this.$root.getListByDocTypeId(6, this.userId, false, this.$root.lettersCount, false);
										this.$parent.getAgreements(this.$parent.agrCount);
										this.$root.getAcquaintances(this.userId);
									} else {
										this.$root.getDocs(this.userId);
									}
									this.refreshInfoDoc();
								}
								this.$root.getDirusers();
								this.isCreating = false;
							}
						} else {
							console.log(this.withoutAgr);
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
			refreshInfoDoc: function() {
				this.clearFile();
				this.docDesc = '';
				this.docFile = '';
				this.docDelivery = null;
				this.baseData.docId = '';
				this.baseData.assignId = '';
				this.docType = null;
				this.newDiruserArr = {
					surname: null,
					firstname: null,
					patronymic: null,
				};
				this.diruser = null;
				this.newDiruser = false;
				this.withoutDiruser = false;
				this.docContractTitle = '';
				this.docContractDeadline = null;
				this.docContractCustomer = '';
				this.docContractCo = '';
				this.docContractShortName = '';
				this.docContractSum = '';
				this.docContractSteps = '';
				this.docInLetterNotice = '';
				this.docInLetterDeadline = null;
				this.docInLetterOuterNum = null; 
				this.docInLetterOuterDate = null;
				this.docOutcomingLetterName = '';
				this.docOutcomingLetterExecutor = '';
				this.docOutcomingLetterOuterNum = null;
				this.docOutcomingLetterOuterDate = null;
				this.docOutcomingLetterNotice = '';
				this.docInIncomingLetterNum = '';
				this.docInIncomingShort = '';
				this.docInIncomingName = '';
				this.docOdExecutor = '';
				this.docAttorneyNum = '';
				this.docAttorneyDate = null;
				this.docAttorneyValidity = null;
				this.docAttorneyFio = '';
				this.docAttorneyPosition = '';
				this.docAttorneyType = false;
				this.docNotifSign = '';
				this.docNotifAuthor = '';
				this.docNotifAsq = null;
				this.deadline = null;
				this.docNum = null;
				this.docDate = null;
				this.$root.baseDocId = null;
		        this.$root.baseAssignId = null;
		        this.$root.baseRefusedDocId = null;
		        this.agreeValue = [];
			},
			uploadFile: function() {
			    this.docFile = this.$refs.file.files[0];
			    this.deleteFileSign = true;
			    document.querySelector('.custom-file-label').innerHTML = this.docFile.name;
			},
			clearFile: function() {
				if (this.oldFile === false) {
					this.docFile = '';
					document.querySelector('.custom-file-label').innerHTML = 'Выберите файл';
					this.deleteFileSign = false;
				};
			},
			getAgreers: function(data) {
				this.agree = data;
			},
			getAgreementType: function(data) {
				this.withoutAgr = data;
			},
			closeMsg: function() {
				this.userMessage = 0;
			},
            getDeliveryTypes: function() {
            	axios.post('api/getdeliverytypes')
					.then(response => {	
						if (response.data.error == 0) {
							this.deliveryTypes = response.data.result;
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
            },
            toggleOldFile: function() {
            	this.oldFile = (this.oldFile === true) ? false : true;
            	console.log(this.oldFile);
            },
		}
	}
</script>