<template>
	<td v-if="dateStatus==0" class="ta-center px-1 timestamp_font">
		<span class="status_refused timestamp_font" title="Срок исполнения уже прошёл...">
		{{ $root.frmtDate(date) }}
	</span>
	</td>
	<td v-else-if="dateStatus==1" class="ta-center px-1 timestamp_font">
		<span class="status_close_to timestamp_font" title="Срок исполнения менее 3 суток...">
			{{ $root.frmtDate(date) }}
		</span>
	</span>
	<td v-else-if="dateStatus==2" class="ta-center px-1 timestamp_font">
		<span class="status_approved timestamp_font" title="Срок исполнения более 3 суток...">
			{{ $root.frmtDate(date) }}
		</span>
	</td>
	<td v-else-if="dateStatus==4" class="ta-center px-1 timestamp_font">
		<span class="status_considering timestamp_font">
			{{ $root.frmtDate(date) }}
		</span>
	</td>
</template>
<script>
	export default {
		props: {
			status: Number,
			date: String,
		},
		data() {
			return {
				dateStatus: 0,
				now: new Date(),
			}
		},
		mounted() {
			this.checkDate(this.date);
		},
		methods: {
			checkDate: function(date) {
				var dt = new Date(date);
				var delta = (dt - this.now)/(24*3600*1000);
				if ((this.status == 1) || (this.status == 6) || (this.status == 7)) {
					if ((dt - this.now) < 0) {
						// console.log('прошло');
						this.dateStatus = 0;
					} else {
						if (delta <= 3) {
							this.dateStatus = 1;
						} else {
							this.dateStatus = 2;
						}
					}
				} else {
					this.dateStatus = 4;
				}
			},
		},
	}

</script>