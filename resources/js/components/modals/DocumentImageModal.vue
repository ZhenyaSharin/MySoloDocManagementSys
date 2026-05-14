<template>
	<div class="modal fade" id="documentImageModal" tabindex="-1" role="dialog" aria-labelledby="documentImageModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title" id="documentImageModalTitle">
						<span class="font-up font-bold">{{ $parent.docData.file.file }}.{{ $parent.docData.file.format }}</span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body p-4">
					<div class="col-md-12 d-flex justify-content-center align-items-center">
<!-- 						<img v-if="$root.storage=='coresar'" :src="'/storage/app/public/images/'+ $parent.docData.file.file + '.' + $parent.docData.file.format" alt="Изображение файла" style="width:inherit;">
						<img v-else :src="'/storage/images/'+ $parent.docData.file.file + '.' + $parent.docData.file.format" alt="Изображение файла" style="width:inherit;"> -->
						<img :src="'/storage/images/'+ $parent.docData.file.file + '.' + $parent.docData.file.format" alt="Изображение файла" style="width:inherit;">
					</div>
				</div>
			</div>
		</div>
	</div>
</template>