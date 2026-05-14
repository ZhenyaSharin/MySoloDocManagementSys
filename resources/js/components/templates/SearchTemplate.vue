<template>
	<div class="col-md-12">
		<div class="card mt-4">
			<div class="card-header card-header_custom font-bold font-up">
				Поиск по документам и поручениям
			</div>
			<div class="newuser_close shad-hover">
				<!-- <i class="fas fa-times fa-lg"/> -->
			</div>
			<div class="card-body">
				<vue-alert-inwork/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group row">
		            		<label for="searchWords" class="col-md-5 col-form-label text-md-right">Ключевые слова:</label>
			            	<div class="col-md-6">
		                        <input type="text" class="form-control" v-model="words" id="searchWords" placeholder="Мин. 3 символа...">
		                        <div v-if="wordsAlert==true" class="status_refused mt-2">
		                        	<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;Неправильно введён запрос
		                        </div>
		                    </div>
		            	</div>
		            </div>
				</div>
				<div class="form-group row">
		        	<label for="deadline" class="col-md-5 col-form-label text-md-right">
		        		Поиск по:
		        	</label>
			        <div class="col-md-3" @click="typeToggle(1)">
	                	<div v-if="$parent.doctypes==true" class="d-flex flex-column mb-2">
	                       	<div class="input-group mb-3">
								<div class="input-group-prepend  cursor-point">
									<div class="input-group-text">
										<input id="docsearch" type="checkbox" aria-label="1й" checked>
									</div>
								</div>
								<input for="docsearch" type="text" class="form-control" aria-label="Text input with checkbox" placeholder="Карточки документов" disabled>
							</div>
	                	</div>
	                	<div v-else class="d-flex flex-column mb-2">
	                       	<div class="input-group mb-3">
								<div class="input-group-prepend  cursor-point">
									<div class="input-group-text">
										<input id="docsearch" type="checkbox" aria-label="1й">
									</div>
								</div>
								<input for="docsearch" type="text" class="form-control" aria-label="Text input with checkbox" placeholder="Карточки документов" disabled>
							</div>
	                	</div>
	                </div>
	                <div class="col-md-3" @click="typeToggle(2)">
	                	<div v-if="$parent.assigntypes==true" class="d-flex flex-column mb-2">
	                       	<div class="input-group mb-3">
								<div class="input-group-prepend cursor-point">
									<div class="input-group-text">
										<input id="assignsearch" type="checkbox" aria-label="1й" checked>
									</div>
								</div>
								<input for="assignsearch" type="text" class="form-control" aria-label="Text input with checkbox" placeholder="Поручения" disabled>
							</div>
	                	</div>
	                	<div v-else class="d-flex flex-column mb-2 cursor-point">
	                       	<div class="input-group mb-3">
								<div class="input-group-prepend cursor-point">
									<div class="input-group-text">
										<input id="assignsearch" type="checkbox" aria-label="1й">
									</div>
								</div>
								<input for="assignsearch" type="text" class="form-control" aria-label="Text input with checkbox" placeholder="Поручения" disabled>
							</div>
	                	</div>
	                </div>
	            </div>
				<div class="form-group row">
		        	<label for="deadline" class="col-md-5 col-form-label text-md-right">
		        		Связанные пользователи:
		        	</label>
			        <div class="col-md-6">
			        	<template v-if="additionalUsers===false">
			        		<div class="d-flex flex-column mb-2">
		                        <vue-multiselect v-model="users" :options="usersList" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" :max="5" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано">
									<template slot="selection" slot-scope="{ values, search, isOpen }">
										<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Пользователей выбрано:&nbsp;{{ values.length }} </span>
									</template>
									<template slot="noOptions" slot-scope="props">
										Список пуст.
									</template>
									<template slot="noResult" slot-scope="props">
										Ничего не найдено...
									</template>
		                        </vue-multiselect>
		                        <template v-for="item in users">
									<div class="font-bold m-2">
										{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
									</div>
		                        </template>
		                	</div>
			        	</template>
			        	<template v-else>
			        		<div class="d-flex flex-column mb-2">
		                        <vue-multiselect :options="usersList" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" :max="5" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано" disabled>
									<template slot="selection" slot-scope="{ values, search, isOpen }">
										<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Пользователей выбрано:&nbsp;{{ values.length }} </span>
									</template>
									<template slot="noOptions" slot-scope="props">
										Список пуст.
									</template>
									<template slot="noResult" slot-scope="props">
										Ничего не найдено...
									</template>
		                        </vue-multiselect>
		                	</div>
			        	</template>
	                </div>
	            </div>
	            <div class="form-group row d-flex justify-content-between" @click="openToggle('additionalUsers')">
		        	<label for="deadline" class="col-md-5 col-form-label text-md-right">
		        		Расширенный поиск по пользователям:
		        	</label>
		        	<div class="col-md-7 py-1 px-2 basetab greyborder-bottom d-flex justify-content-between align-items-center">
		        		<div class="ta-center font-bold">
			        		Раскрыть поиск:
			        	</div>
		        		<div v-bind:class="[{ basetab__angle_rotated: additionalUsers }, angleAdditionalUsers]">
			        		<i class="fas fa-caret-down fa-2x"/>
			        	</div>
		        	</div>
		        </div>
		        <div v-if="additionalUsers===true">
			        <template v-if="$parent.doctypes==true">
			        	<div class="form-group row mb-1">
				        	<label for="deadline" class="col-md-5 col-form-label text-md-right">
				        		Автор документа:
				        	</label>
					        <div class="col-md-6">
			                	<div class="d-flex flex-column mb-2">
			                        <vue-multiselect v-model="usersDocsAuthor" :options="usersList" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" :max="5" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано">
										<template slot="selection" slot-scope="{ values, search, isOpen }">
											<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Пользователей выбрано:&nbsp;{{ values.length }} </span>
										</template>
										<template slot="noOptions" slot-scope="props">
											Список пуст.
										</template>
										<template slot="noResult" slot-scope="props">
											Ничего не найдено...
										</template>
			                        </vue-multiselect>
			                        <template v-for="item in usersDocsAuthor">
										<div class="font-bold m-2">
											{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
										</div>
			                        </template>
			                	</div>
			                	<template v-if="$parent.assigntypes==true">
			                		<hr>
			                	</template>
			                </div>
			            </div>
			        </template>
			        <template v-if="$parent.assigntypes==true">
			        	<div class="form-group row mb-1">
				        	<label for="deadline" class="col-md-5 col-form-label text-md-right">
				        		Автор поручения:
				        	</label>
					        <div class="col-md-6">
			                	<div class="d-flex flex-column mb-2">
			                        <vue-multiselect v-model="usersAssignsAuthor" :options="usersList" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" :max="5" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано">
										<template slot="selection" slot-scope="{ values, search, isOpen }">
											<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Пользователей выбрано:&nbsp;{{ values.length }} </span>
										</template>
										<template slot="noOptions" slot-scope="props">
											Список пуст.
										</template>
										<template slot="noResult" slot-scope="props">
											Ничего не найдено...
										</template>
			                        </vue-multiselect>
			                        <template v-for="item in usersAssignsAuthor">
										<div class="font-bold m-2">
											{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
										</div>
			                        </template>
			                	</div>
			                </div>
			            </div>
			            <div class="form-group row">
				        	<label for="deadline" class="col-md-5 col-form-label text-md-right">
				        		Исполнитель поручения:
				        	</label>
					        <div class="col-md-6">
			                	<div class="d-flex flex-column mb-2">
			                        <vue-multiselect v-model="usersAssignsExecutor" :options="usersList" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" :max="5" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано">
										<template slot="selection" slot-scope="{ values, search, isOpen }">
											<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Пользователей выбрано:&nbsp;{{ values.length }} </span>
										</template>
										<template slot="noOptions" slot-scope="props">
											Список пуст.
										</template>
										<template slot="noResult" slot-scope="props">
											Ничего не найдено...
										</template>
			                        </vue-multiselect>
			                        <template v-for="item in usersAssignsExecutor">
										<div class="font-bold m-2">
											{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
										</div>
			                        </template>
			                	</div>
			                </div>
			            </div>
			            <hr>
			        </template>
			    </div>
			    <br/>
	            <div class="form-group row">
                    <label for="contractDate" class="col-12 col-md-5 col-form-label text-md-right">
                    	Период создания:
                    </label>
                    <div class="col-12 col-md-6">
                        <vue-datepicker valueType="format" format="DD.MM.YYYY" v-model="period" class="cursor-point wid-inherit" range style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
                    </div>
                </div>
	            <div v-if="$parent.doctypes==true" class="form-group row">
                    <label for="contractDate" class="col-12 col-md-5 col-form-label text-md-right">
                    	Период даты документа:
                    </label>
                    <div class="col-8 col-md-4">
                    	<vue-datepicker v-if="withoutDocDate==true" valueType="format" format="DD.MM.YYYY" class="cursor-point wid-inherit" range style="width: inherit;" placeholder="ДД.ММ.ГГГГ" disabled/>
                        <vue-datepicker v-else valueType="format" format="DD.MM.YYYY" v-model="docDate" class="cursor-point wid-inherit" range style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
                    </div>
                	<div class="col-4 col-md-3" @click="toggleDocDate()">
	                	<div v-if="withoutDocDate==true" class="d-flex flex-column">
	                       	<div class="input-group mb-3">
								<div class="input-group-prepend  cursor-point">
									<div class="input-group-text">
										<input id="toggleDocDate" type="checkbox" aria-label="1й" checked>
									</div>
								</div>
								<input for="toggleDocDate" type="text" class="form-control" aria-label="Text input with checkbox" placeholder="Без даты" disabled>
							</div>
	                	</div>
	                	<div v-else class="d-flex flex-column">
	                       	<div class="input-group mb-3">
								<div class="input-group-prepend  cursor-point">
									<div class="input-group-text">
										<input id="toggleDocDate" type="checkbox" aria-label="1й">
									</div>
								</div>
								<input for="toggleDocDate" type="text" class="form-control" aria-label="Text input with checkbox" placeholder="Без даты" disabled>
							</div>
	                	</div>
                    </div>
                </div>
                <div v-if="$parent.doctypes==true" class="form-group row mb-4">
                    <label for="contractDate" class="col-12 col-md-5 col-form-label text-md-right">
                    	Номер документа:
                    </label>
                    <div class="col-12 col-md-6">
                        <input type="text" class="form-control" v-model="orderNum" id="searchWords" placeholder="Введите номер док-та (письма и т.д.)">
<!-- 	                        <div v-if="wordsAlert==true" class="status_refused mt-2">
                        	<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;Неправильно введён запрос
                        </div> -->
                    </div>
                </div>
	            <div v-if="$parent.doctypes==true" class="form-group row">
            		<label for="deadline" class="col-md-5 col-form-label text-md-right">
		        		Типы документов:
		        	</label>
			        <div class="col-md-7">
			        	<div class="row">
		            		<div class="col-md-12 p-2 basetab greyborder-bottom d-flex justify-content-center align-items-center" @click="openToggle('docTypes')">
	            				<div class="ta-center font-bold">
					        		Список типов документов:
					        	</div>
					        	<div v-bind:class="[{ basetab__angle_rotated: docTypesPanel }, angleDocTypes]">
					        		<i class="fas fa-caret-down fa-2x"/>
					        	</div>
					        </div>
			            	<template v-if="docTypesPanel">
				        		<div class="col-md-12 mt-1 d-flex flex-wrap">
				        			<div class="d-flex mr-2 mb-2" v-for="(item, index) in $root.documentTypes">
				                       	<div v-if="docTypesArr.includes(item.id)" class="d-flex flex-wrap greyrow">
											<div class="p-2">
												<input :id="'doctype'+index" type="checkbox" aria-label="1й" :value="item.id" v-model="docTypesArr" checked>
											</div>
											<label class="py-2 pr-3 m-0 cursor-point" :for="'doctype'+index">
												{{ item.title }}
											</label>
										</div>
										<div v-else class="d-flex flex-wrap greyrow">
											<div class="p-2">
												<input :id="'doctype'+index" type="checkbox" aria-label="1й" v-model="docTypesArr" :value="item.id">
											</div>
											<label class="py-2 pr-3 m-0 cursor-point" :for="'doctype'+index">
												{{ item.title }}
											</label>
										</div>
				                	</div>
				                	<div v-if="$root.documentTypes.length==docTypesArr.length" class="d-flex mr-2 mb-2">
				                       	<a class="d-flex flex-wrap">
											<label class="p-2 pr-3 m-0 cursor-point font-bold" @click="clearTypes(1)">
												Убрать все...
											</label>
										</a>
				                	</div>
				                	<div v-if="$root.documentTypes.length>docTypesArr.length" class="d-flex mr-2 mb-2">
				                       	<a class="d-flex flex-wrap">
											<label class="p-2 pr-3 m-0 cursor-point font-bold" @click="getTypes(1)">
												Выбрать все...
											</label>
										</a>
				                	</div>
				        		</div>
			            	</template>
			        	</div>
			        	<div v-if="docTypesAlert==true" class="status_refused mt-2">
                        	<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;Вы не выбрали тип документа
                        </div>
	                </div>
		        </div>
	            <div v-if="$parent.assigntypes==true" class="form-group row">
		        	<label for="deadline" class="col-md-5 col-form-label text-md-right">
		        		Типы поручений:
		        	</label>
			        <div class="col-md-7">
			        	<div class="row">
		        			<div class="col-md-12 p-2 basetab greyborder-bottom d-flex justify-content-center align-items-center" @click="openToggle('assignTypes')">
	            				<div class="ta-center font-bold">
					        		Список типов поручений:
					        	</div>
					        	<div v-bind:class="[{ basetab__angle_rotated: assignTypesPanel }, angleAssignTypes]">
					        		<i class="fas fa-caret-down fa-2x"/>
					        	</div>
					        </div>
			            	<template v-if="assignTypesPanel">
				        		<div class="col-md-12 mt-1 d-flex flex-wrap">
				        			<div class="d-flex mr-2 mb-2" v-for="(item, index) in assignmentTypes">
				                       	<div v-if="assignTypesArr.includes(item.id)" class="d-flex flex-wrap greyrow">
											<div class="p-2">
												<input :id="'assigntype'+index" type="checkbox" aria-label="1й" :value="item.id" v-model="assignTypesArr" checked>
											</div>
											<label class="py-2 pr-3 m-0 cursor-point" :for="'assigntype'+index">
												{{ item.title }}
											</label>
										</div>
										<div v-else class="d-flex flex-wrap greyrow">
											<div class="p-2">
												<input :id="'assigntype'+index" type="checkbox" aria-label="1й" :value="item.id" v-model="assignTypesArr" checked>
											</div>
											<label class="py-2 pr-3 m-0 cursor-point" :for="'assigntype'+index">
												{{ item.title }}
											</label>
										</div>
				                	</div>
				                	<div v-if="assignmentTypes.length==assignTypesArr.length" class="d-flex mr-2 mb-2">
				                       	<a class="d-flex flex-wrap">
											<label class="p-2 pr-3 m-0 cursor-point font-bold" @click="clearTypes(2)">
												Убрать все...
											</label>
										</a>
				                	</div>
				        			<div v-else-if="assignmentTypes.length>assignTypesArr.length" class="d-flex mr-2 mb-2">
				                       	<a class="d-flex flex-wrap">
											<label class="p-2 pr-3 m-0 cursor-point font-bold" @click="getTypes(2)">
												Выбрать все...
											</label>
										</a>
				                	</div>
				        		</div>
				        	</template>
			        	</div>
			        	<div v-if="assignTypesAlert==true" class="status_refused mt-2">
                        	<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;Вы не выбрали тип поручений
                        </div>
	                </div>
	            </div>
	            <div v-if="$parent.doctypes==true" class="form-group row">
		        	<label for="deadline" class="col-md-5 col-form-label text-md-right">
		        		Статусы документов:
		        	</label>
			        <div class="col-md-7">
			        	<div class="row">
		        			<div class="col-md-12 p-2 basetab greyborder-bottom d-flex justify-content-center align-items-center" @click="openToggle('docStatuses')">
	            				<div class="ta-center font-bold">
					        		Список статусов документов:
					        	</div>
					        	<div v-bind:class="[{ basetab__angle_rotated: docStatusesPanel }, angleDocStatuses]">
					        		<i class="fas fa-caret-down fa-2x"/>
					        	</div>
					        </div>
			            	<template v-if="docStatusesPanel">
				        		<div class="col-md-12 mt-1 d-flex flex-wrap">
			        				<div v-for="(item, index) in documentStatuses" class="d-flex mr-2 mb-2">
				                       	<div v-if="docStatusesArr.includes(item.id)" class="d-flex flex-wrap greenrow">
											<div class="p-2">
												<input :id="'docstatus'+index" type="checkbox" aria-label="1й" :value="item.id" v-model="docStatusesArr" checked>
											</div>
											<label class="py-2 pr-3 m-0 cursor-point" :for="'docstatus'+index">
												{{ item.title }}
											</label>
										</div>
										<div v-else class="d-flex flex-wrap greenrow">
											<div class="p-2">
												<input :id="'docstatus'+index" type="checkbox" aria-label="1й" :value="item.id" v-model="docStatusesArr">
											</div>
											<label class="py-2 pr-3 m-0 cursor-point" :for="'docstatus'+index">
												{{ item.title }}
											</label>
										</div>
				                	</div>
				                	<div v-if="documentStatuses.length==docStatusesArr.length" class="d-flex mr-2 mb-2">
				                       	<a class="d-flex flex-wrap" @click="clearStatuses(1)">
											<label class="p-2 pr-3 m-0 cursor-point font-bold">
												Убрать все...
											</label>
										</a>
				                	</div>
				        			<div v-else-if="documentStatuses.length>docStatusesArr.length" class="d-flex mr-2 mb-2">
				                       	<a class="d-flex flex-wrap" @click="getStatuses(1)">
											<label class="p-2 pr-3 m-0 cursor-point font-bold">
												Выбрать все...
											</label>
										</a>
				                	</div>
				        		</div>
				        	</template>
			        	</div>
			        	<div v-if="docStatusesAlert==true" class="status_refused mt-2">
                        	<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;Вы не выбрали статус документа
                        </div>
	                </div>
	            </div>
	            <div v-if="$parent.assigntypes==true" class="form-group row">
		        	<label for="deadline" class="col-md-5 col-form-label text-md-right">
		        		Статусы поручений:
		        	</label>
			        <div class="col-md-7">
			        	<div class="row">
		        			<div class="col-md-12 p-2 basetab greyborder-bottom d-flex justify-content-center align-items-center" @click="openToggle('assignStatuses')">
	            				<div class="ta-center font-bold">
					        		Список статусов поручений:
					        	</div>
					        	<div v-bind:class="[{ basetab__angle_rotated: assignStatusesPanel }, angleDocStatuses]">
					        		<i class="fas fa-caret-down fa-2x"/>
					        	</div>
					        </div>
			            	<template v-if="assignStatusesPanel">
				        		<div class="col-md-12 mt-1 d-flex flex-wrap">
			        				<div v-for="(item, index) in assignmentStatuses" class="d-flex mr-2 mb-2">
				                       	<div v-if="assignStatusesArr.includes(item.id)" class="d-flex flex-wrap greenrow">
											<div class="p-2">
												<input :id="'assignstatus'+index" type="checkbox" aria-label="1й" :value="item.id" v-model="assignStatusesArr" checked>
											</div>
											<label class="py-2 pr-3 m-0 cursor-point" :for="'assignstatus'+index">
												{{ item.title }}
											</label>
										</div>
										<div v-else class="d-flex flex-wrap greenrow">
											<div class="p-2">
												<input :id="'assignstatus'+index" type="checkbox" aria-label="1й" :value="item.id" v-model="assignStatusesArr">
											</div>
											<label class="py-2 pr-3 m-0 cursor-point" :for="'assignstatus'+index">
												{{ item.title }}
											</label>
										</div>
				                	</div>
				                	<div v-if="assignmentStatuses.length==assignStatusesArr.length" class="d-flex mr-2 mb-2">
				                       	<a class="d-flex flex-wrap" @click="clearStatuses(2)">
											<label class="p-2 pr-3 m-0 cursor-point font-bold">
												Убрать все...
											</label>
										</a>
				                	</div>
				        			<div v-if="assignmentStatuses.length>assignStatusesArr.length" class="d-flex mr-2 mb-2">
				                       	<a class="d-flex flex-wrap" @click="getStatuses(2)">
											<label class="p-2 pr-3 m-0 cursor-point font-bold">
												Выбрать все...
											</label>
										</a>
				                	</div>
				        		</div>
				        	</template>
			        	</div>
			        	<div v-if="assignStatusesAlert==true" class="status_refused mt-2">
                        	<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;Вы не выбрали статус получений
                        </div>
	                </div>
	            </div>
	            <div v-if="$parent.doctypes==true||$parent.assigntypes==true" class="form-group row">
		            <div class="col-md-12 offset-md-5 my-4">
		            	<a v-if="isSearching==true" class="btn btn-primary btn__creation font-bold no-round font-up p-3 box-shad ta-center" disabled>
		                    <vue-spinner/>
		                </a>
		                <a v-else class="btn btn-primary btn__creation font-bold no-round font-up p-3 box-shad" @click="search()">
		                    <i class="fas fa-search fa-lg"/>&nbsp;&nbsp;Поиск
		                </a>
		            </div>
		        </div>
		        <div v-else class="form-group row">
		            <div class="col-md-12 offset-md-5 my-4">
		                <button class="btn btn-primary btn__creation font-bold no-round font-up p-3 box-shad" disabled>
		                    <i class="fas fa-search fa-lg"/>&nbsp;&nbsp;Поиск
		                </button>
		            </div>
		        </div>
        		<div class="row" v-if="$parent.searchMessage==true">
					<div class="col-md-12" v-if="$parent.doctypes!==false||$parent.assigntypes!==false">
						<div class="alert alert-primary d-flex justify-content-between align-items-center" role="alert">
							<div>
								Найдено<span v-if="$parent.doctypes==true">&nbsp;документов:&nbsp;<span class="font-bold">{{ $parent.countDocs }}</span></span><span v-if="$parent.assigntypes==true">&nbsp;поручений:&nbsp;<span class="font-bold">{{ $parent.countAssign }}</span></span>
							</div>
							<div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="$parent.closeMsg()">
					        	<i class="fas fa-times fa-lg"/>
					        </div>
						</div>
					</div>
					<div class="col-md-12" v-else>
						<div class="alert alert-primary d-flex justify-content-between align-items-center" role="alert">
							<div>
								Не выбраны "Поиск по:"
							</div>
							<div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="$parent.closeMsg()">
					        	<i class="fas fa-times fa-lg"/>
					        </div>
						</div>
					</div>
				</div>
		        <template v-if="result!=null">
		        	<div v-if="$parent.doctypes==true&&$parent.assigntypes==true" class="row">
				        <br/>
						<div class="col-md-6 table__search_scroll_y mb-2">
							<table class="table table-hover">
								<thead class="thead-dark font-up">
									<tr>
										<th width="12%" scope="col">
											ID
										</th>
										<th width="40%" scope="col">
											Карточка документа
										</th>
										<th width="28%" class="ta-center" scope="col">
											Номер док-та
										</th>
										<th width="20%" class="ta-right" scope="col">
											Тип
										</th>
									</tr>
								</thead>
								<template v-if="spinSearchDocs===true">
									<tbody>
										<tr>
											<td colspan="100%" class="ta-center mt-4">
												<vue-spinner/>
											</td>
										</tr>
									</tbody>
								</template>
								<template v-else>
									<tbody v-if="result.documents.length>0">
										<template v-for="item in result.documents">
											<vue-item-search-document-short :data="item"/>
										</template>
									</tbody>
									<tbody v-else>
										<tr>
											<th colspan="100%" class="ta-center font-up tr-greyplug">
												Ничего не найдено...
											</th>
										</tr>
									</tbody>
								</template>
							</table>
						</div>
						<div class="col-md-6 table__search_scroll_y mb-2">
							<table class="table table-hover">
								<thead class="thead-dark font-up">
									<tr>
										<th width="12%" scope="col">
											ID
										</th>
										<th width="40%" scope="col">
											Поручение
										</th>
										<th width="28%" class="ta-center" scope="col">
											Срок
										</th>
										<th width="20%" class="ta-right" scope="col">
											Тип
										</th>
									</tr>
								</thead>
								<template v-if="spinSearchAssigns===true">
									<tbody>
										<tr>
											<td colspan="100%" class="ta-center mt-4">
												<vue-spinner/>
											</td>
										</tr>
									</tbody>
								</template>
								<template v-else>
									<tbody v-if="result.assignments.length>0">
										<template v-for="item in result.assignments">
											<vue-item-search-assignment-short :data="item"/>
										</template>
									</tbody>
									<tbody v-else>
										<tr>
											<th colspan="100%" class="ta-center font-up tr-greyplug">
												Ничего не найдено
											</th>
										</tr>
									</tbody>
								</template>
							</table>
						</div>
					</div>
					<div v-else-if="$parent.doctypes==true" class="row">
						<div class="col-md-12 table__search_scroll_y">
							<table class="table table-hover">
								<thead class="thead-dark font-up">
									<tr>
										<th width="10%" scope="col">
											ID
										</th>
										<th width="40%" scope="col">
											Карточка документа
										</th>
										<th width="20%" class="ta-center" scope="col">
											Номер док-та
										</th>
										<th width="30%" class="ta-right" scope="col">
											Тип
										</th>
									</tr>
								</thead>
								<template v-if="spinSearchDocs===true">
									<tbody>
										<tr>
											<td colspan="100%" class="ta-center mt-4">
												<vue-spinner/>
											</td>
										</tr>
									</tbody>
								</template>
								<template v-else>
									<tbody v-if="result.documents.length>0">
										<template v-for="item in result.documents">
											<vue-item-search-document :data="item"/>
										</template>
									</tbody>
									<tbody v-else>
										<tr>
											<th colspan="100%" class="ta-center font-up tr-greyplug">
												Ничего не найдено...
											</th>
										</tr>
									</tbody>
								</template>
							</table>
						</div>
					</div>
					<div v-else-if="$parent.assigntypes==true" class="row">
						<div class="col-md-12 table__search_scroll_y">
							<table class="table table-hover">
								<thead class="thead-dark font-up">
									<tr>
										<th width="10%" scope="col">
											ID
										</th>
										<th width="40%" scope="col">
											Поручение
										</th>
										<th width="30%" class="ta-center" scope="col">
											Срок исполнения
										</th>
										<th width="20%" class="ta-right" scope="col">
											Тип
										</th>
									</tr>
								</thead>
								<template v-if="spinSearchAssigns===true">
									<tbody>
										<tr>
											<td colspan="100%" class="ta-center mt-4">
												<vue-spinner/>
											</td>
										</tr>
									</tbody>
								</template>
								<template v-else>
									<tbody v-if="result.assignments.length>0">
										<template v-for="item in result.assignments">
											<vue-item-search-assignment :data="item"/>
										</template>
									</tbody>
									<tbody v-else>
										<tr>
											<th colspan="100%" class="ta-center font-up tr-greyplug">
												Ничего не найдено
											</th>
										</tr>
									</tbody>
								</template>
							</table>
						</div>
					</div>
				</template>
			</div>
		</div>
    </div>
</template>	
<script>
	import DatePicker from 'vue2-datepicker';
  	import 'vue2-datepicker/index.css';

	export default {
		components: { 
			DatePicker,
		},
		props: {
			usersList: Array,
		},
		data() {
			return {
				// users: null,
				isSearching: false,
				words: null,
				docDate: null,
				period: null,
				result: null,
				users: [],
				orderNum: '',
				assignmentTypes: [],
				documentStatuses: [],
				assignmentStatuses: [],
				docStatusesArr: [],
				assignStatusesArr: [],
				docTypesArr:  [],
				assignTypesArr: [],
				docResult: [],
				assignResult: [],
				spinSearchDocs: false,
				spinSearchAssigns: false,
				createRange: null,
				wordsAlert: false,
				docTypesAlert: false,
				assignTypesAlert: false,
				docStatusesAlert: false,
				assignStatusesAlert: false,
				arr: {},
				statuses: [],
				angleDocTypes: 'basetab__angle',
				docTypesPanel: false,
				angleAssignTypes: 'basetab__angle',
				assignTypesPanel: false,
				angleDocStatuses: 'basetab__angle',
				docStatusesPanel: false,
				angleAssignStatuses: 'basetab__angle',
				assignStatusesPanel: false,
				withoutDocDate: false,
				additionalUsers: false,
				angleAdditionalUsers: 'basetab__angle',
				usersDocsAuthor: [],
				usersAssignsAuthor: [],
				usersAssignsExecutor: [],
			}
		},
		mounted() {
			this.arr = this.startData();
			if (this.arr.searchOn == 1) {
				this.docTypesArr = this.arr.docTypes;
				this.assignTypesArr = this.arr.assignTypes;
				this.docStatusesArr = this.arr.docStatuses;
				this.assignStatusesArr = this.arr.assignStatuses;
				this.docDate = this.arr.docDate;
				this.period = this.arr.period;
				this.words = this.arr.words;
				this.orderNum = this.arr.orderNum;
				// this.users = this.arr.users;
				this.$root.getUsersList(this.$parent.userId)
				console.log(this.arr);
				if (this.arr.users.length !== 0) {
					let users = [];
					console.log(this.usersList);
					// this.usersList.forEach(item => { 
						if (this.arr.users.includes(31) === true) {
							console.log('dsv');
							// users.push(item);
						};
						this.users = users;
					// });
				};
				console.log(this.users);
				this.docTypesPanel = (this.arr.docTypes.length != 0) ? true : false;
				this.assignTypesPanel = (this.arr.assignTypes.length != 0) ? true : false;
				this.docStatusesPanel = (this.arr.docStatuses.length != 0) ? true : false;
				this.assignStatusesPanel = (this.arr.assignStatuses.length != 0) ? true : false;

				this.search();
				// console.log(this.docTypesArr);
			};
			this.$root.getDocumentTypes();
			axios.post('api/getassignmenttypes', {
				headers: {
					"Content-Type": "application/json"
				}
			})
				.then(response => {
					if (response.data.error == 0) {
						this.assignmentTypes = response.data.result;
						if (this.arr.searchOn != 1) {
							this.getTypes(2);
						}
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
			// this.getStatuses();
			axios.post('api/getstatuses', {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.statuses = response.data.result;
							this.getStatuses();
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			// if (this.$route.query.searchOn == 1) {
			// 	this.search();
			// };
		},
		methods: {
			openToggle: function(type) {
				if (type === 'docTypes') {
					this.docTypesPanel = (this.docTypesPanel === false) ? true : false;
					this.docTypesAlert = false;
					this.docTypesArr = [];
				} else if (type === 'assignTypes') {
					this.assignTypesPanel = (this.assignTypesPanel === false) ? true : false;
					this.assignTypesAlert = false;
					this.assignTypesArr = [];
				} else if (type === 'docStatuses') {
					this.docStatusesPanel = (this.docStatusesPanel === false) ? true : false;
					this.docStatusesAlert = false;
					this.docStatusesArr = [];
				} else if (type === 'assignStatuses') {
					this.assignStatusesPanel = (this.assignStatusesPanel === false) ? true : false;
					this.assignStatusesAlert = false;
					this.assignStatusesArr = [];
				} else if (type === 'additionalUsers') {
					this.additionalUsers = (this.additionalUsers === false) ? true : false;
					// this.assignStatusesAlert = false;
					this.usersDocsAuthor = [];
					this.usersAssignsAuthor = [];
					this.usersAssignsExecutor = [];
					this.users = [];
				};
				
			},
			search: function() {
				console.log(this.words);
				this.isSearching = true;
				this.spinSearchDocs = true;
				this.spinSearchAssigns = true;
				if (this.words != null ) {
					if (this.words.length > 0) {
						console.log(this.words + 'тще тгдд');
						// this.arr.words.trim();
						if (this.words.trim().length >= 3) {
							this.wordsAlert = false;
							this.arr.words = this.words;
						} else {
							this.wordsAlert = true;
							this.arr.words = null;
						}
					} else {
						this.words = null;
						this.arr.words = null;
					}
				}

				if (this.orderNum != null) {
					this.arr.orderNum = this.orderNum.trim();
				}

				if (this.users != []) {
					// console.log('notnull');
					let users = [];
					this.users.forEach(item => {
						users.push(item.id);
					});
					this.arr.users = JSON.stringify(users);
				} else {
					this.arr.users = null;
				}

				if (this.docTypesPanel === true) {
					if (this.docTypesArr != false) {
						this.arr.docTypes = JSON.stringify(this.docTypesArr);
						this.docTypesAlert = false;
					} else {
						this.docTypesAlert = true;
					};
				} else {
					this.docTypesArr = [];
					this.arr.docTypes = null;
				};

				if (this.assignTypesPanel === true) {
					if (this.assignTypesArr != false) {
						this.arr.assignTypes = JSON.stringify(this.assignTypesArr);
						this.assignTypesAlert = false;
					} else {
						this.assignTypesAlert = true;
					};
				} else {
					this.assignTypesArr = [];
					this.arr.assignTypes = null;
				};

				if (this.docStatusesPanel === true) {
					if (this.docStatusesArr != false) {
						this.arr.docStatuses = JSON.stringify(this.docStatusesArr);
						this.docStatusesAlert = false;
					} else {
						this.docStatusesAlert = true;
					};
				} else {
					this.docStatusesArr = [];
					this.arr.docStatuses = null;
				};


				if (this.assignStatusesPanel === true) {
					if (this.assignStatusesArr != false) {
						this.arr.assignStatuses = JSON.stringify(this.assignStatusesArr);
						this.assignStatusesAlert = false;
					} else {
						this.assignStatusesAlert = true;
					};
				} else {
					this.assignStatusesArr = [];
					this.arr.assignStatuses = null;
				};

				if (this.withoutDocDate == true) {
						this.arr.docDate = 0;
				} else {
					if (this.docDate != null) {
						this.arr.docDate = ((this.docDate[0] != null) && (this.docDate[this.docDate.length - 1] != null)) ? JSON.stringify(this.$root.frmtDateRangeIn(this.docDate)) : null;
					} else {
						this.arr.docDate = null;
					}
				}

				if (this.period != null) {
					this.arr.period = ((this.period[0] != null) && (this.period[this.period.length - 1] != null)) ? JSON.stringify(this.$root.frmtDateRangeIn(this.period)) : null;
				}

				if (this.additionalUsers === true) {
					this.arr.additionalUsers = 1;
					this.arr.users = null;
					// this.arr.docsAuthor = (this.usersDocsAuthor != []) ? JSON.stringify(this.usersDocsAuthor) : null;
					// this.arr.assignAuthor = (this.usersAssignsAuthor != []) ? JSON.stringify(this.usersAssignsAuthor) : null;
					// this.arr.assignExecutor = (this.usersAssignsExecutor != []) ? JSON.stringify(this.usersAssignsExecutor) : null;

					// usersAssignsAuthor: [],
					// usersAssignsExecutor: [],
					// let users = [];
					// this.users.forEach(item => {
					// 	users.push(item.id);
					// });

					if (this.usersDocsAuthor != []) {
						let users = [];
						this.usersDocsAuthor.forEach(item => {
							users.push(item.id);
						});
						this.arr.docAuthor = JSON.stringify(users);
					};

					if (this.usersAssignsAuthor != []) {
						let users = [];
						this.usersAssignsAuthor.forEach(item => {
							users.push(item.id);
						});
						this.arr.assignAuthor = JSON.stringify(users);
					};

					if (this.usersAssignsExecutor != []) {
						let users = [];
						this.usersAssignsExecutor.forEach(item => {
							users.push(item.id);
						});
						this.arr.assignExecutor = JSON.stringify(users);
					};
				} else {
					this.arr.docsAuthor = null;
					this.arr.assignAuthor = null;
					this.arr.assignExecutor = null;
					this.arr.additionalUsers = 0;
				};

				if ((this.wordsAlert == false) && (this.docTypesAlert == false) && (this.assignTypesAlert == false) && (this.docStatusesAlert == false) && (this.assignStatusesAlert == false)) {
					this.arr.searchOn = 1;

				console.log(this.arr);

				this.$router.push({ name: this.$route.name, query: this.arr })
						.catch(err => {});
				axios.post('api/makesearch', this.arr, {
			        headers: {
			        	"Content-Type": "application/json"
			        }
			    })
					.then(response => {
						if (response.data.error == 0) {
							this.result = response.data.result;
							if (this.$parent.doctypes == true) {
								this.$parent.countDocs = this.result.documents.length;
								console.log(this.result.documents);
							};
							if (this.$parent.assigntypes == true) {
								this.$parent.countAssign = this.result.assignments.length;
								console.log(this.result.assignments);
							};
							this.$parent.searchMessage = true;
							this.isSearching = false;
							this.spinSearchDocs = false;
							this.spinSearchAssigns = false;
						} else {
							// this.userMessage = 2;
							this.isSearching = false;
							this.spinSearchDocs = false;
							this.spinSearchAssigns = false;
						}
					}).catch(error => {
						// this.userMessage = 2;
						alert('Ошибка получения данных');
						console.log(error);
						this.isSearching = false;
						this.spinSearchDocs = false;
						this.spinSearchAssigns = false;
					});
				} else {
					this.isSearching = false;
					this.spinSearchDocs = false;
					this.spinSearchAssigns = false;
					this.arr.words = null;
				}
			},
			typeToggle: function(type) {
				if (type == 1) {
					this.$parent.doctypes = (this.$parent.doctypes == false) ? true : false;
				} else if (type == 2) {
					this.$parent.assigntypes = (this.$parent.assigntypes == false) ? true : false;
				}
			},
			getStatuses: function(type = null) {
				if (type == 1) {	
					this.docStatusesArr = [];
					this.statuses.forEach(item => {
						if (item.group == 1) {
							this.docStatusesArr.push(item.id);
						}
					});
				} else if (type == 2) {
					this.assignStatusesArr = [];
					this.statuses.forEach(item => {
						if (item.group == 2) {
							this.assignStatusesArr.push(item.id);
						}
					});
				} else {
					this.statuses.forEach(item => {
						if (item.group == 1) {
							this.documentStatuses.push(item);
							if (this.arr.searchOn != 1) {
								this.docStatusesArr.push(item.id);
							}
						} else if (item.group == 2) {
							this.assignmentStatuses.push(item);
							if (this.arr.searchOn != 1) {
								this.assignStatusesArr.push(item.id);
							}
						}
					});
				}
			},
			getTypes: function(type) {
				if (type == 1) {
					this.docTypesArr = [];
					this.$root.documentTypes.forEach(item => {
						this.docTypesArr.push(item.id);
					});
				} else if (type == 2) {
					this.assignTypesArr = [];
					this.assignmentTypes.forEach(item => {
						this.assignTypesArr.push(item.id);
					});
				}
			},
			clearTypes: function(type = null) {
				if (type == 1) {
					this.docTypesArr = [];
				} else if (type == 2) {
					this.assignTypesArr = [];
				}
			},
			clearStatuses: function(type) {
				if (type == 1) {	
					this.docStatusesArr = [];
				} else if (type == 2) { 
					this.assignStatusesArr = [];
				}
			},
			makeFio: function(surname, firstname, patronymic = null) {
				if (patronymic == null) {
					return surname + ' ' + firstname.slice(0, 1).toUpperCase() + '.';
				} else {
					return surname + ' ' + firstname.slice(0, 1).toUpperCase() + '. ' + patronymic.slice(0, 1).toUpperCase() + '.';
				}
			},
			startData: function() {
				// console.log(JSON.parse(this.$route.query.docTypes));
				let arr = {};
				arr.words = (this.$route.query.words != null) ? this.$route.query.words : null;
				arr.orderNum = (this.$route.query.orderNum != null) ? this.$route.query.orderNum : null;
				arr.users = (this.$route.query.users != null) ? JSON.parse(this.$route.query.users) : null;
				console.log(arr.orderNum);
				arr.docTypes = (this.$route.query.docTypes != null) ? JSON.parse(this.$route.query.docTypes) : this.docTypesArr;
				arr.assignTypes = (this.$route.query.assignTypes != null) ? JSON.parse(this.$route.query.assignTypes) : this.assignTypesArr;
				arr.docStatuses = (this.$route.query.docStatuses != null) ? JSON.parse(this.$route.query.docStatuses) : this.docStatusesArr;
				arr.assignStatuses = (this.$route.query.assignStatuses != null) ? JSON.parse(this.$route.query.assignStatuses) : this.assignStatusesArr;
				arr.period = (this.$route.query.period != null) ? JSON.parse(this.$route.query.period) : null;
				arr.searchOn = (this.$route.query.searchOn != null) ? this.$route.query.searchOn : null;
				// arr.docDate = (this.$route.query.docDate != null) ? JSON.parse(this.$route.query.docDate) : null;

				if (this.$route.query.docDate != null) {
					if (this.$route.query.docDate == 0) {
						this.withoutDocDate = true;
						arr.docDate = null;
					} else {
						this.withoutDocDate = false;
						arr.docDate = JSON.parse(this.$route.query.docDate)
					}
				} else {
					arr.docDate = null;
				}

				// arr.searchDocs = (this.$route.query.searchDocs != null) ? this.$route.query.searchDocs : null;
				// arr.searchAssigns = (this.$route.query.searchAssigns != null) ? this.$route.query.searchAssigns : null;
				return arr;
			},
			toggleDocDate: function() {
				this.withoutDocDate = (this.withoutDocDate === false) ? true : false;
				this.docDate = null;
				if (this.arr.docDate) {
					this.arr.docDate = null;
				};
			}
		},
	}
</script>