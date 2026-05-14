<template>
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="saveDocInfoModalLabel" aria-hidden="true" id="saveDocInfoModal">
	    <div class="modal-dialog modal-dialog-centered" role="document">
	        <div class="modal-content no-round">
	            <div class="modal-header">
	                <h5 class="modal-title font-bold font-up" id="saveDocInfoModalLabel">Сохранить изменения в документе?</h5>
	            </div>
	            <div v-if="file===true" class="modal-footer d-flex">
	                <button class="btn btn-primary no-round flex-sm-fill">
	                    Сохранить файл
	                </button>
	                <button type="button" class="btn btn-secondary no-round flex-fill" data-dismiss="modal">
	                    Отмена
	                </button>
	            </div>
	            <div v-else class="modal-footer d-flex">
	                <button class="btn btn-primary no-round flex-fill" @click="$parent.editDoc()">
	                    Сохранить
	                </button>
	                <button type="button" class="btn btn-secondary no-round flex-fill" data-dismiss="modal">
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
			file: {
				type: Boolean,
				default: false,
			}
		},
	}
</script>	