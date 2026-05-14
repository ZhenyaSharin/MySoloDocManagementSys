<template>
	<div class="modal fade" id="getFromArchiveModal" tabindex="-1" role="dialog" aria-labelledby="addInArchiveTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content no-round">
				<div class="modal-header">
					<h5 class="modal-title" id="addInArchiveTitle">
						<span class="font-up font-bold">Восстановить из архива:</span>&nbsp;
						<br/>
						&laquo;{{ docData.description }}&raquo;
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-footer ta-center">
					<button class="btn btn-primary no-round flex-fill" @click="$root.addInArchive(docData.id, 3)">
	                    Подтвердить
	                </button>
	                <button class="btn btn-danger no-round flex-fill" data-dismiss="modal">
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
			docData: Object,
		},
		methods: {
			// 
		}
	}
</script>