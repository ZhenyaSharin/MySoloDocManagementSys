<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="pdf__modal_label" aria-hidden="true" id="pdf__modal">
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
            <div class="modal-body">
                @include('modals.pdfpreview')
            </div>
            <div class="modal-footer d-flex">
                <a class="btn btn-primary no-round flex-sm-fill" href="{{ route('docpdf') }}" title="Сохранить в PDF" >
                    <i class="fas fa-file-download fa-lg">&nbsp;&nbsp;</i>Сохранить как PDF
                </a>
                <button type="button" class="btn btn-danger no-round flex-sm-fill" data-dismiss="modal">
                    Закрыть
                </button>
            </div>
        </div>
    </div>
</div>