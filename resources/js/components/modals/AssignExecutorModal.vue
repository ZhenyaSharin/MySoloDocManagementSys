<template>
	<div class="modal fade bd-example-modal-lg" id="assignExecutorModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content no-round">
				<div class="modal-header">
                    <h5 class="modal-title font-up font-bold" id="assignExecutorModalLabel">
                    	Рассмотреть поручение
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="$parent.refreshExecutor()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <div class="changetime">
                        <h4>
                            Перенести срок
                        </h4>
                        <vue-datepicker id="deadline" v-model="deadline" valueType="format" type="datetime" format="DD.MM.YYYY" :disabled-date="datePickerOps.disabledDate" class="cursor-point" placeholder="ДД.ММ.ГГГГ"/>
                    </div>
                </div>
                <div class="modal-footer justify-content-center d-flex">
                    <button type="button" class="btn btn-primary no-round font-up flex-fill" @click="$parent.refreshExecutor()">
                    	Одобрить
                    </button>
                    <button type="button" class="btn btn-success no-round font-up flex-fill" @click="$parent.refreshExecutor()">
                        Одобрить с переносом срока
                    </button>
                    <button type="button" class="btn btn-danger no-round font-up flex-fill" @click="$parent.refreshExecutor()">
                        Отклонить
                    </button>
                </div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
        props: {
            userId: String,
            deadline: String,
        },
        data() {
            return {
                // datePickerOps: {
                //     disabledDate: (date) => date < new Date(),
                // },
            }
        },
        mounted() {
            console.log(this.deadline);
        }, 
        methods: {
            // 
        }
    }