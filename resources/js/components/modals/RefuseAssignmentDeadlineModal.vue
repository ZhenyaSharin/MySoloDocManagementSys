<template>
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="refuseAssignDeadlineModalLabel" aria-hidden="true" id="refuseAssignDeadlineModal">
	    <div class="modal-dialog modal-dialog-centered" role="document">
	        <div class="modal-content no-round">
	            <div class="modal-header">
	                <h5 class="modal-title font-bold font-up" id="refuseAssignDeadlineModalLabel">Отклонить новый срок исполнения?</h5>
	            </div>
	            <div class="modal-footer d-flex">
	                <button class="btn btn-danger no-round flex-fill" @click="$parent.refuseNewDeadline()">
	                    Отклонить
	                </button>
	                <button type="button" class="btn btn-secondary no-round flex-fill" data-dismiss="modal">
	                    Отмена
	                </button>
	            </div>
	        </div>
	    </div>
	</div>
</template>