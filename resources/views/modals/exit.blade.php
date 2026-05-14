<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exit__acc_modal_label1" aria-hidden="true" id="exit__acc_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content no-round">
            <div class="modal-header">
                <h5 class="modal-title font-bold" id="exit__acc_modal_label1">Вы действительно хотите выйти?</h5>
<!--                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-footer d-flex">
                <a class="btn btn-primary no-round flex-fill" href="{{ route('logout') }}" title="Выйти" 
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                    Выйти
                </a>
                <button type="button" class="btn btn-danger no-round flex-fill" data-dismiss="modal">
                    Отмена
                </button>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>