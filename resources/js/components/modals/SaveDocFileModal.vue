<template>
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="saveDocFileModalLabel" aria-hidden="true" id="saveDocFileModal">
	    <div class="modal-dialog modal-dialog-centered" role="document">
	        <div class="modal-content no-round">
	            <div class="modal-header">
	                <h5 class="modal-title font-bold font-up" id="saveDocFileModalLabel">Сохранить файл в документе?</h5>
	            </div>
	            <div class="modal-footer d-flex">
	                <button class="btn btn-primary no-round flex-fill" @click="$parent.editFileDoc()">
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