<template>
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="refuseDocModalLabel" aria-hidden="true" id="refuseDocModal">
	    <div class="modal-dialog modal-dialog-centered" role="document">
	        <div class="modal-content no-round">
	            <div class="modal-header">
	                <h5 class="modal-title font-bold" id="refuseDocModalLabel">Отклонить согласование?</h5>
	            </div>
	            <div v-if="$parent.needComment==true">
	            	<div class="alert-red m-4">
                        <i class="fas fa-exclamation-circle fa-lg"/>&nbsp;Укажите причину
                    </div>
	            </div>
	            <div class="modal-footer d-flex">
	                <button class="btn btn-danger no-round flex-fill"  @click="$parent.refuseAgreement($parent.actualAgreement, $parent.isAgree, $parent.userId)" data-dismiss="modal">
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