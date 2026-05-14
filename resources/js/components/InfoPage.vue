<template>
	<div class="container">
		<div class="row">
			<div class="col-md-12 d-flex justify-content-between align-items-center">
				<div>
					<h1>
						Руководство по эксплуатации (тестовое):
					</h1>
				</div>
			</div>
		</div>
		<div class="custom-h3">
			&nbsp;Тестовое, редакция от {{ date }}:
		</div>
		<div class="row">
			<div class="col-md-12 d-flex justify-content-center align-items-center mt-2">
				<div class="d-flex justify-content-center">
                    <!-- <iframe src="/storage/RE_11082022.pdf" width='1140' height='690' allowfullscreen webkitallowfullscreen/> -->
                    <iframe src="/storage/RE_13012025.pdf" width='1140' height='690' allowfullscreen webkitallowfullscreen/>
                </div>
			</div>
			<br/>
			<a href='/storage/RE_11082022.pdf' class="btn btn-success no-round font-bold font-up btn_larger box-shad mt-4" target="_blank">
				<i class="fas fa-file-pdf fa-lg"/>&nbsp;Открыть редакцию от 11.08.2022
			</a>
		</div>
	</div>
</template>
<script>
	// import axios from 'axios';
	// import ResponsesListItem;

	export default {
		props: {
			userId: String,
			roleId: {
				default: 2,
				type: Number,
			},
		},
		data() {
			return {
				date: '13.01.2025',
			}
		},
	}
</script>