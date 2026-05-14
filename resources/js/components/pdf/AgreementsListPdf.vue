<template>
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="pdf__agreementslist_label" aria-hidden="true" id="pdf__agreementslist">
	    <div class="modal-dialog modal-lg" role="document">
	        <div class="modal-content no-round">
	            <div class="modal-header">
	                <h5 class="modal-title font-bold" id="pdf__modal_label">
	                    Предварительный просмотр PDF-файла...
	                </h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body" id="agreementsListPdf">
	                <vue-template-аgreementpreview :list="list" :data="data"/>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
	export default {
		props: {
			list: Object,
			data: Object,
		},
	}
</script>